<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Event;
use App\Models\FeedComment;
use App\Models\FeedLike;
use App\Models\FeedShare;
use App\Models\Job;
use Illuminate\Support\Facades\Schema;

class NewsfeedController extends Controller
{
    public function index()
    {
        $feedItems = collect();

        $feedItems = $feedItems->merge(
            $this->loadFeedModels(Job::class, 'job')
        );

        $feedItems = $feedItems->merge(
            $this->loadFeedModels(Event::class, 'event')
        );

        $feedItems = $feedItems->merge(
            $this->loadFeedModels(Donation::class, 'donation')
        );

        $feedItems = $feedItems
            ->sortByDesc('rank_score')
            ->values();

        return view('newsfeed.index', compact('feedItems'));
    }

    private function loadFeedModels(string $modelClass, string $type)
    {
        if (!class_exists($modelClass)) {
            return collect();
        }

        $model = new $modelClass();
        $table = $model->getTable();

        if (!Schema::hasTable($table)) {
            return collect();
        }

        $query = $modelClass::query();

        if (Schema::hasColumn($table, 'status')) {
            $allowedStatuses = match ($type) {
                'job' => ['approved', 'active', 'published', 'open'],
                'event', 'donation' => ['approved', 'active', 'published'],
                default => ['approved', 'active', 'published'],
            };

            $query->whereIn('status', $allowedStatuses);
        }

        /*
         * Do not hard-code a "jobs" table here.
         * The Job model decides whether the real table is jobs/job_postings.
         */

        if ($type === 'job') {
            if (Schema::hasColumn($table, 'deadline')) {
                $query->where(function ($q) {
                    $q->whereNull('deadline')
                        ->orWhereDate('deadline', '>=', today());
                });
            }

            if (Schema::hasColumn($table, 'application_deadline')) {
                $query->where(function ($q) {
                    $q->whereNull('application_deadline')
                        ->orWhereDate('application_deadline', '>=', today());
                });
            }
        }

        /*
         * Keep a reasonable server-side candidate pool.
         * The Blade shows 12 first and Load More reveals the rest.
         */
        return $query
            ->latest()
            ->take(60)
            ->get()
            ->map(fn ($item) => $this->makeFeedItem($type, $item));
    }

    private function makeFeedItem(string $type, $model): array
    {
        $feedableType = get_class($model);
        $feedableId = (int) $model->id;

        $likesCount = $this->countTable(
            FeedLike::class,
            'feed_likes',
            $feedableType,
            $feedableId
        );

        $commentsCount = $this->countTable(
            FeedComment::class,
            'feed_comments',
            $feedableType,
            $feedableId
        );

        $sharesCount = $this->countTable(
            FeedShare::class,
            'feed_shares',
            $feedableType,
            $feedableId
        );

        $createdAt = $model->created_at ?? now();
        $ageHours = max(0, now()->diffInHours($createdAt));

        /*
         * Freshness is primary.
         * Engagement gives a useful boost, but is deliberately lighter than before
         * so an old viral item does not dominate the feed for too long.
         */
        $recencyScore = max(0, 168 - $ageHours);
        $engagementScore =
            ($likesCount * 2)
            + ($commentsCount * 4)
            + ($sharesCount * 5);

        $rankScore = $recencyScore + $engagementScore;

        return [
            'feedable_type' => $feedableType,
            'feedable_id' => $feedableId,
            'type' => $type,

            'title' => $model->title ?? $this->defaultTitle($type),
            'description' => $model->description ?? '',
            'image' => $model->cover_image
                ?? $model->image
                ?? $model->job_image
                ?? null,

            'badge' => $this->badge($type),
            'icon' => $this->icon($type),
            'color' => $this->color($type),

            'date' => $createdAt,
            'url' => $this->url($type, $model),
            'meta' => $this->meta($type, $model),

            'likes_count' => $likesCount,
            'comments_count' => $commentsCount,
            'shares_count' => $sharesCount,
            'rank_score' => $rankScore,

            'liked_by_me' => $this->likedByMe($feedableType, $feedableId),
            'recent_comments' => $this->recentComments($feedableType, $feedableId),
        ];
    }

    private function countTable(
        string $modelClass,
        string $table,
        string $type,
        int $id
    ): int {
        if (!class_exists($modelClass) || !Schema::hasTable($table)) {
            return 0;
        }

        return $modelClass::where('feedable_type', $type)
            ->where('feedable_id', $id)
            ->count();
    }

    private function likedByMe(string $type, int $id): bool
    {
        if (!auth()->check() || !Schema::hasTable('feed_likes')) {
            return false;
        }

        return FeedLike::where('user_id', auth()->id())
            ->where('feedable_type', $type)
            ->where('feedable_id', $id)
            ->exists();
    }

    private function recentComments(string $type, int $id)
    {
        if (!Schema::hasTable('feed_comments')) {
            return collect();
        }

        return FeedComment::with('user')
            ->where('feedable_type', $type)
            ->where('feedable_id', $id)
            ->latest()
            ->take(3)
            ->get();
    }

    private function defaultTitle(string $type): string
    {
        return match ($type) {
            'job' => 'Job Opportunity',
            'event' => 'University Event',
            'donation' => 'Donation Campaign',
            default => 'Newsfeed Post',
        };
    }

    private function badge(string $type): string
    {
        return match ($type) {
            'job' => 'Job Opportunity',
            'event' => 'Event',
            'donation' => 'Donation Campaign',
            default => 'Post',
        };
    }

    private function icon(string $type): string
    {
        return match ($type) {
            'job' => 'fa-briefcase',
            'event' => 'fa-calendar-days',
            'donation' => 'fa-hand-holding-heart',
            default => 'fa-newspaper',
        };
    }

    private function color(string $type): string
    {
        return match ($type) {
            'job' => 'from-cyan-500 to-blue-600',
            'event' => 'from-purple-500 to-pink-600',
            'donation' => 'from-emerald-500 to-teal-600',
            default => 'from-slate-500 to-slate-700',
        };
    }

    private function url(string $type, $model): string
    {
        return match ($type) {
            'job' => route('jobs.show', $model),
            'event' => route('events.index'),
            'donation' => route('donations.show', $model),
            default => route('newsfeed.index'),
        };
    }

    private function meta(string $type, $model): array
    {
        return match ($type) {
            'job' => [
                $model->company_name ?? $model->company ?? null,
                $model->location ?? null,
                $model->type ?? null,
            ],

            'event' => [
                $model->location ?? null,
                $model->start_date ?? $model->event_date ?? null,
                $model->type ?? null,
            ],

            'donation' => [
                $model->category ?? null,
                isset($model->target_amount)
                    ? 'Target: ' . $model->target_amount
                    : null,
                $model->deadline ?? null,
            ],

            default => [],
        };
    }
}
