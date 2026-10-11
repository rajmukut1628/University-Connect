<?php

use App\Http\Middleware\UnblockExpiredAccounts;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

uses(TestCase::class);

beforeEach(function () {
    config(['database.default' => 'sqlite', 'database.connections.sqlite.database' => ':memory:']);
    Date::setTestNow('2026-10-11 12:00:00');
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->boolean('is_blocked')->default(false);
        $table->boolean('is_active')->default(true);
        $table->text('blocked_reason')->nullable();
        $table->timestamps();
    });
    (require base_path('database/migrations/2026_10_11_180000_add_blocked_until_to_users_table.php'))->up();
});

afterEach(function () {
    Date::setTestNow();
});

test('a temporary block lasts until its exact expiry and then clears', function () {
    $user = User::create([
        'is_blocked' => true,
        'blocked_until' => now()->addDays(7),
        'blocked_reason' => 'Temporary restriction',
    ]);

    Date::setTestNow('2026-10-18 11:59:59');
    expect(User::unblockExpiredAccounts())->toBe(0);
    expect($user->fresh()->is_blocked)->toBeTrue();

    Date::setTestNow('2026-10-18 12:00:00');
    expect(User::unblockExpiredAccounts())->toBe(1);
    expect($user->fresh()->is_blocked)->toBeFalse()
        ->and($user->fresh()->blocked_until)->toBeNull()
        ->and($user->fresh()->blocked_reason)->toBeNull();
    expect(User::unblockExpiredAccounts())->toBe(0);
});

test('permanent blocks and future blocks remain blocked', function () {
    $permanent = User::create(['is_blocked' => true]);
    $future = User::create(['is_blocked' => true, 'blocked_until' => now()->addDay()]);

    expect(User::unblockExpiredAccounts())->toBe(0);
    expect($permanent->fresh()->is_blocked)->toBeTrue()
        ->and($future->fresh()->is_blocked)->toBeTrue();
});

test('expiry does not activate an independently inactive account', function () {
    $user = User::create([
        'is_blocked' => true,
        'is_active' => false,
        'blocked_until' => now()->subSecond(),
    ]);

    User::unblockExpiredAccounts();
    expect($user->fresh()->is_blocked)->toBeFalse()
        ->and($user->fresh()->is_active)->toBeFalse();
});

test('web requests unblock expired accounts before access checks', function () {
    $user = User::create(['is_blocked' => true, 'blocked_until' => now()]);
    $response = (new UnblockExpiredAccounts)->handle(Request::create('/login'), function () use ($user) {
        expect($user->fresh()->is_blocked)->toBeFalse();

        return new Response('OK');
    });

    expect($response->getContent())->toBe('OK');
});
