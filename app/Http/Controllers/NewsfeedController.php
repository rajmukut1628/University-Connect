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

        if (class_exists(Job::class) && Schema::hasTable('jobs')) {
            $jobsQuery = Job::query();

            if (Schema::hasColumn('jobs', 'status')) {
                $jobsQuery->whereIn('status', ['approved', 'active', 'published']);
            }

            $jobs = $jobsQuery->latest()->take(30)->get()->map(function ($job) {
                return $this->makeFeedItem('job', $job);
            });

            $feedItems = $feedItems->merge($jobs);
        }

        if (class_exists(Event::class) && Schema::hasTable('events')) {
            $eventsQuery = Event::query();

            if (Schema::hasColumn('events', 'status')) {
                $eventsQuery->whereIn('status', ['approved', 'active', 'published']);
            }

            $events = $eventsQuery->latest()->take(30)->get()->map(function ($event) {
                return $this->makeFeedItem('event', $event);
            });

            $feedItems = $feedItems->merge($events);
        }

        if (class_exists(Donation::class) && Schema::hasTable('donations')) {
            $donationsQuery = Donation::query();

            if (Schema::hasColumn('donations', 'status')) {
                $donationsQuery->whereIn('status', ['approved', 'active', 'published']);
            }

            $donations = $donationsQuery->latest()->take(30)->get()->map(function ($donation) {
                return $this->makeFeedItem('donation', $donation);
            });

            $feedItems = $feedItems->merge($donations);
        }

        $feedItems = $feedItems
            ->sortByDesc('rank_score')
            ->values();

        return view('newsfeed.index', compact('feedItems'));
    }

    private function makeFeedItem(string $type, $model): array
    {
        $feedableType = get_class($model);
        $feedableId = $model->id;

        $likesCount = $this->countTable(FeedLike::class, 'feed_likes', $feedableType, $feedableId);
        $commentsCount = $this->countTable(FeedComment::class, 'feed_comments', $feedableType, $feedableId);
        $sharesCount = $this->countTable(FeedShare::class, 'feed_shares', $feedableType, $feedableId);

        $createdAt = $model->created_at ?? now();

        $ageHours = max(1, now()->diffInHours($createdAt));

        // Recent + Reach ranking
        // Recent post আগে থাকবে, কিন্তু বেশি like/comment/share থাকলে কয়েকদিন boost পাবে
        $recencyScore = max(0, 120 - $ageHours);
        $engagementScore = ($likesCount * 3) + ($commentsCount * 6) + ($sharesCount * 8);

        $rankScore = $recencyScore + $engagementScore;

        return [
            'feedable_type' => $feedableType,
            'feedable_id' => $feedableId,
            'type' => $type,

            'title' => $model->title ?? $this->defaultTitle($type),
            'description' => $model->description ?? '',
            'image' => $model->cover_image ?? $model->image ?? null,

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

    private function countTable(string $modelClass, string $table, string $type, int $id): int
    {
        if (!class_exists($modelClass) || !Schema::hasTable($table)) {
            return 0;
        }

        return $modelClass::where('feedable_type', $type)
            ->where('feedable_id', $id)
            ->count();
    }

    private function likedByMe(string $type, int $id): bool
    {
        if (!Schema::hasTable('feed_likes')) {
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
                isset($model->target_amount) ? 'Target: ' . $model->target_amount : null,
                $model->deadline ?? null,
            ],
            default => [],
        };
    }
}