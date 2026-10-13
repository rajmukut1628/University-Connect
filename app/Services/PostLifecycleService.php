<?php

namespace App\Services;

use App\Models\{Donation, Event, JobPosting};
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class PostLifecycleService
{
    public const TYPES = ['job' => JobPosting::class, 'event' => Event::class, 'donation' => Donation::class];

    public static function expiry($post): ?Carbon
    {
        if ($post instanceof Event) {
            $date = $post->end_date ?? $post->start_date;
            return $date ? Carbon::parse($date->format('Y-m-d').' '.($post->end_time ?: '23:59:59')) : $post->event_date?->copy()->endOfDay();
        }
        return $post->deadline ? Carbon::parse($post->deadline)->endOfDay() : null;
    }

    public static function accepting($post): bool
    {
        $expiry = self::expiry($post);
        return !$post->archived_at && (!$expiry || $expiry->gte(now()));
    }

    public static function archiveExpired(): void
    {
        foreach (self::TYPES as $type => $class) {
            $table = (new $class)->getTable();
            if (!Schema::hasColumn($table, 'archived_at')) continue;
            $query = $class::whereNull('archived_at');
            if ($type !== 'event') {
                $query->whereDate('deadline', '<', today())->update(['archived_at' => now()]);
            } else {
                $query->where(function ($q) {
                    $q->whereDate('end_date', '<=', today())
                        ->orWhere(function ($q) { $q->whereNull('end_date')->whereDate('start_date', '<=', today()); })
                        ->orWhere(function ($q) { $q->whereNull('end_date')->whereNull('start_date')->where('event_date', '<', now()); });
                })->chunkById(100, function ($events) {
                    foreach ($events as $event) {
                        if (self::expiry($event)?->lt(now())) $event->update(['archived_at' => now()]);
                    }
                });
            }
        }
    }
}
