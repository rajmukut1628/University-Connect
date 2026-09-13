<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\VerifiedUser;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncVerifiedUsersToAccounts extends Command
{
    protected $signature = 'users:sync-verified';

    protected $description = 'Create missing login accounts from verified users';

    public function handle(): int
    {
        $created = 0;
        $skipped = 0;
        $failed = 0;

        $verifiedUsers = VerifiedUser::whereIn('role', ['student', 'alumni'])
            ->get();

        $this->info('Found ' . $verifiedUsers->count() . ' verified users.');

        foreach ($verifiedUsers as $verified) {

            $officialId = $verified->student_id
                ?? $verified->alumni_id;

            if (!$officialId) {
                $this->warn(
                    "Skipped {$verified->email}: Official ID missing."
                );

                $skipped++;
                continue;
            }

            $existingByOfficialId = User::where(
                'official_id',
                $officialId
            )->first();

            if ($existingByOfficialId) {
                $this->line(
                    "Skipped {$officialId}: account already exists."
                );

                $skipped++;
                continue;
            }

            $existingByEmail = User::where(
                'email',
                $verified->email
            )->first();

            if ($existingByEmail) {
                $this->warn(
                    "Skipped {$officialId}: email already exists in users table."
                );

                $skipped++;
                continue;
            }

            try {

                DB::transaction(function () use (
                    $verified,
                    $officialId
                ) {
                    User::create([
                        'name' => $verified->name,
                        'email' => strtolower(
                            trim($verified->email)
                        ),
                        'official_id' => $officialId,

                        /*
                         * Initial password = Official ID
                         */
                        'password' => $officialId,

                        'role' => $verified->role,

                        'phone' => $verified->phone,
                        'department' => $verified->department,
                        'batch' => $verified->batch,

                        /*
                         * Important:
                         * Do not put human Official ID
                         * into these FK columns.
                         */
                        'student_id' => null,
                        'alumni_id' => null,

                        'email_verified_at' => now(),
                        'email_verified' => true,

                        'is_active' =>
                            $verified->status === 'active',

                        'is_blocked' => false,
                        'is_owner' => false,
                    ]);
                });

                $this->info(
                    "Created account: {$officialId} - {$verified->email}"
                );

                $created++;

            } catch (\Throwable $e) {

                $this->error(
                    "Failed {$officialId}: " . $e->getMessage()
                );

                $failed++;
            }
        }

        $this->newLine();

        $this->info('Sync completed.');

        $this->table(
            ['Created', 'Skipped', 'Failed'],
            [[
                $created,
                $skipped,
                $failed,
            ]]
        );

        return self::SUCCESS;
    }
}