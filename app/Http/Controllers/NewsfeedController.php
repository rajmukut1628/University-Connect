<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Event;
use App\Models\FeedComment;
use App\Models\FeedLike;
use App\Models\FeedShare;
use App\Models\JobPosting;
use Illuminate\Support\Facades\Schema;

class NewsfeedController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Newsfeed
    |--------------------------------------------------------------------------
    |
    | Newsfeed combines:
    |
    | - Approved / Published Jobs
    | - Approved / Active / Published Events
    | - Approved / Active / Published Donations
    |
    */

    public function index()
    {
        $feedItems = collect();

        /*
        |--------------------------------------------------------------------------
        | Jobs
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        | The project uses JobPosting model, not Job model.
        |
        */

        $feedItems = $feedItems->merge(
            $this->loadFeedModels(
                JobPosting::class,
                'job'
            )
        );

        /*
        |--------------------------------------------------------------------------
        | Events
        |--------------------------------------------------------------------------
        */

        $feedItems = $feedItems->merge(
            $this->loadFeedModels(
                Event::class,
                'event'
            )
        );

        /*
        |--------------------------------------------------------------------------
        | Donations
        |--------------------------------------------------------------------------
        */

        $feedItems = $feedItems->merge(
            $this->loadFeedModels(
                Donation::class,
                'donation'
            )
        );

        /*
        |--------------------------------------------------------------------------
        | Final Ranking
        |--------------------------------------------------------------------------
        */

        $feedItems = $feedItems
            ->sortByDesc('rank_score')
            ->values();

        return view(
            'newsfeed.index',
            compact('feedItems')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Load Feed Models
    |--------------------------------------------------------------------------
    */

    private function loadFeedModels(
        string $modelClass,
        string $type
    ) {
        /*
        |--------------------------------------------------------------------------
        | Model Safety Check
        |--------------------------------------------------------------------------
        */

        if (!class_exists($modelClass)) {
            return collect();
        }

        $model = new $modelClass();

        /*
        |--------------------------------------------------------------------------
        | Automatically Detect Correct Table
        |--------------------------------------------------------------------------
        |
        | JobPosting -> job_postings
        | Event      -> events
        | Donation   -> donations
        |
        */

        $table = $model->getTable();

        if (!Schema::hasTable($table)) {
            return collect();
        }


        /*
        |--------------------------------------------------------------------------
        | Start Query
        |--------------------------------------------------------------------------
        */

        $query = $modelClass::query();


        /*
        |--------------------------------------------------------------------------
        | Status Filtering
        |--------------------------------------------------------------------------
        |
        | Pending and rejected posts should NOT appear in Newsfeed.
        |
        */

        if (Schema::hasColumn($table, 'status')) {

            $allowedStatuses = match ($type) {

                /*
                 * Current JobPosting system uses "approved".
                 *
                 * active / published / open are kept for compatibility
                 * if the project uses them in the future.
                 */

                'job' => [
                    'approved',
                    'active',
                    'published',
                    'open',
                ],

                /*
                 * Event system supports these public statuses.
                 */

                'event' => [
                    'approved',
                    'active',
                    'published',
                ],

                /*
                 * Donation currently normally uses approved.
                 * Other public values are kept for compatibility.
                 */

                'donation' => [
                    'approved',
                    'active',
                    'published',
                ],

                default => [
                    'approved',
                    'active',
                    'published',
                ],
            };

            $query->whereIn(
                'status',
                $allowedStatuses
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Job Deadline Protection
        |--------------------------------------------------------------------------
        |
        | Expired jobs should not continue appearing in Newsfeed.
        |
        */

        if ($type === 'job') {

            if (
                Schema::hasColumn(
                    $table,
                    'deadline'
                )
            ) {

                $query->where(function ($q) {

                    $q->whereNull('deadline')
                        ->orWhereDate(
                            'deadline',
                            '>=',
                            today()
                        );
                });
            }


            /*
             * Compatibility:
             * If another migration uses application_deadline.
             */

            if (
                Schema::hasColumn(
                    $table,
                    'application_deadline'
                )
            ) {

                $query->where(function ($q) {

                    $q->whereNull(
                        'application_deadline'
                    )
                        ->orWhereDate(
                            'application_deadline',
                            '>=',
                            today()
                        );
                });
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Load Feed Candidates
        |--------------------------------------------------------------------------
        |
        | Up to 60 items of each type are loaded.
        | Blade handles initial display + Load More.
        |
        */

        return $query
            ->latest()
            ->take(60)
            ->get()
            ->map(
                fn ($item) =>
                    $this->makeFeedItem(
                        $type,
                        $item
                    )
            );
    }


    /*
    |--------------------------------------------------------------------------
    | Convert Model To Newsfeed Item
    |--------------------------------------------------------------------------
    */

    private function makeFeedItem(
        string $type,
        $model
    ): array {

        /*
        |--------------------------------------------------------------------------
        | Polymorphic Identity
        |--------------------------------------------------------------------------
        |
        | Job:
        | App\Models\JobPosting
        |
        | Event:
        | App\Models\Event
        |
        | Donation:
        | App\Models\Donation
        |
        */

        $feedableType = get_class($model);

        $feedableId = (int) $model->id;


        /*
        |--------------------------------------------------------------------------
        | Engagement Counts
        |--------------------------------------------------------------------------
        */

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


        /*
        |--------------------------------------------------------------------------
        | Post Date
        |--------------------------------------------------------------------------
        */

        $createdAt =
            $model->created_at
            ?? now();


        /*
        |--------------------------------------------------------------------------
        | Ranking
        |--------------------------------------------------------------------------
        |
        | Fresh posts receive higher priority.
        |
        | Likes    = 2 points
        | Comments = 4 points
        | Shares   = 5 points
        |
        */

        $ageHours = max(
            0,
            now()->diffInHours(
                $createdAt
            )
        );


        $recencyScore = max(
            0,
            168 - $ageHours
        );


        $engagementScore =
            ($likesCount * 2)
            +
            ($commentsCount * 4)
            +
            ($sharesCount * 5);


        $rankScore =
            $recencyScore
            +
            $engagementScore;


        /*
        |--------------------------------------------------------------------------
        | Build Newsfeed Item
        |--------------------------------------------------------------------------
        */

        return [

            /*
            |--------------------------------------------------------------------------
            | Identity
            |--------------------------------------------------------------------------
            */

            'feedable_type' =>
                $feedableType,

            'feedable_id' =>
                $feedableId,

            'type' =>
                $type,


            /*
            |--------------------------------------------------------------------------
            | Content
            |--------------------------------------------------------------------------
            */

            'title' =>
                $model->title
                ?? $this->defaultTitle(
                    $type
                ),


            'description' =>
                $model->description
                ?? '',


            /*
            |--------------------------------------------------------------------------
            | Image
            |--------------------------------------------------------------------------
            |
            | Event:
            | cover_image
            |
            | Donation:
            | image
            |
            | Job:
            | job_image
            |
            */

            'image' =>
                $model->cover_image
                ?? $model->image
                ?? $model->job_image
                ?? null,


            /*
            |--------------------------------------------------------------------------
            | Appearance
            |--------------------------------------------------------------------------
            */

            'badge' =>
                $this->badge(
                    $type
                ),

            'icon' =>
                $this->icon(
                    $type
                ),

            'color' =>
                $this->color(
                    $type
                ),


            /*
            |--------------------------------------------------------------------------
            | Navigation
            |--------------------------------------------------------------------------
            */

            'date' =>
                $createdAt,

            'url' =>
                $this->url(
                    $type,
                    $model
                ),

            'meta' =>
                $this->meta(
                    $type,
                    $model
                ),


            /*
            |--------------------------------------------------------------------------
            | Engagement
            |--------------------------------------------------------------------------
            */

            'likes_count' =>
                $likesCount,

            'comments_count' =>
                $commentsCount,

            'shares_count' =>
                $sharesCount,

            'rank_score' =>
                $rankScore,


            /*
            |--------------------------------------------------------------------------
            | Current User State
            |--------------------------------------------------------------------------
            */

            'liked_by_me' =>
                $this->likedByMe(
                    $feedableType,
                    $feedableId
                ),


            /*
            |--------------------------------------------------------------------------
            | Recent Comments
            |--------------------------------------------------------------------------
            */

            'recent_comments' =>
                $this->recentComments(
                    $feedableType,
                    $feedableId
                ),
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Count Likes / Comments / Shares
    |--------------------------------------------------------------------------
    */

    private function countTable(
        string $modelClass,
        string $table,
        string $type,
        int $id
    ): int {

        if (
            !class_exists($modelClass)
            ||
            !Schema::hasTable($table)
        ) {
            return 0;
        }


        return $modelClass::where(
            'feedable_type',
            $type
        )
            ->where(
                'feedable_id',
                $id
            )
            ->count();
    }


    /*
    |--------------------------------------------------------------------------
    | Check Current User Like
    |--------------------------------------------------------------------------
    */

    private function likedByMe(
        string $type,
        int $id
    ): bool {

        if (
            !auth()->check()
            ||
            !Schema::hasTable(
                'feed_likes'
            )
        ) {
            return false;
        }


        return FeedLike::where(
            'user_id',
            auth()->id()
        )
            ->where(
                'feedable_type',
                $type
            )
            ->where(
                'feedable_id',
                $id
            )
            ->exists();
    }


    /*
    |--------------------------------------------------------------------------
    | Recent Comments
    |--------------------------------------------------------------------------
    */

    private function recentComments(
        string $type,
        int $id
    ) {

        if (
            !Schema::hasTable(
                'feed_comments'
            )
        ) {
            return collect();
        }


        return FeedComment::with(
            'user'
        )
            ->where(
                'feedable_type',
                $type
            )
            ->where(
                'feedable_id',
                $id
            )
            ->latest()
            ->take(3)
            ->get();
    }


    /*
    |--------------------------------------------------------------------------
    | Default Title
    |--------------------------------------------------------------------------
    */

    private function defaultTitle(
        string $type
    ): string {

        return match ($type) {

            'job' =>
                'Job Opportunity',

            'event' =>
                'University Event',

            'donation' =>
                'Donation Campaign',

            default =>
                'Newsfeed Post',
        };
    }


    /*
    |--------------------------------------------------------------------------
    | Badge
    |--------------------------------------------------------------------------
    */

    private function badge(
        string $type
    ): string {

        return match ($type) {

            'job' =>
                'Job Opportunity',

            'event' =>
                'Event',

            'donation' =>
                'Donation Campaign',

            default =>
                'Post',
        };
    }


    /*
    |--------------------------------------------------------------------------
    | Icon
    |--------------------------------------------------------------------------
    */

    private function icon(
        string $type
    ): string {

        return match ($type) {

            'job' =>
                'fa-briefcase',

            'event' =>
                'fa-calendar-days',

            'donation' =>
                'fa-hand-holding-heart',

            default =>
                'fa-newspaper',
        };
    }


    /*
    |--------------------------------------------------------------------------
    | Gradient Color
    |--------------------------------------------------------------------------
    */

    private function color(
        string $type
    ): string {

        return match ($type) {

            'job' =>
                'from-cyan-500 to-blue-600',

            'event' =>
                'from-purple-500 to-pink-600',

            'donation' =>
                'from-emerald-500 to-teal-600',

            default =>
                'from-slate-500 to-slate-700',
        };
    }


    /*
    |--------------------------------------------------------------------------
    | Detail URL
    |--------------------------------------------------------------------------
    */

    private function url(
        string $type,
        $model
    ): string {

        return match ($type) {

            'job' =>
                route(
                    'jobs.show',
                    $model
                ),

            /*
             * Current Event routes use events.index.
             */

            'event' =>
                route(
                    'events.index'
                ),

            'donation' =>
                route(
                    'donations.show',
                    $model
                ),

            default =>
                route(
                    'newsfeed.index'
                ),
        };
    }


    /*
    |--------------------------------------------------------------------------
    | Feed Metadata
    |--------------------------------------------------------------------------
    */

    private function meta(
        string $type,
        $model
    ): array {

        return match ($type) {

            /*
            |--------------------------------------------------------------------------
            | Job Meta
            |--------------------------------------------------------------------------
            */

            'job' => [

                $model->company_name
                    ?? $model->company
                    ?? null,

                $model->location
                    ?? null,

                $model->type
                    ?? null,
            ],


            /*
            |--------------------------------------------------------------------------
            | Event Meta
            |--------------------------------------------------------------------------
            */

            'event' => [

                $model->location
                    ?? null,

                $model->start_date
                    ?? $model->event_date
                    ?? null,

                $model->type
                    ?? null,
            ],


            /*
            |--------------------------------------------------------------------------
            | Donation Meta
            |--------------------------------------------------------------------------
            */

            'donation' => [

                $model->category
                    ?? null,

                isset(
                    $model->target_amount
                )
                    ? 'Target: ' .
                        $model->target_amount
                    : null,

                $model->deadline
                    ?? null,
            ],


            default => [],
        };
    }
}