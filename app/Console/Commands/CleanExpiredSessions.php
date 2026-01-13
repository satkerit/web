<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use App\Models\AuditTrail;

class CleanExpiredSessions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sessions:clean-expired
                            {--dry-run : Show what would be cleaned without actually cleaning}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean expired user activity sessions from cache';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        $idleTimeout = Config::get('security.idle_timeout', 30) * 60; // Convert to seconds
        $currentTime = now()->timestamp;
        $cleanedCount = 0;
        $totalChecked = 0;

        $this->info('Starting expired session cleanup...');
        $this->info("Idle timeout: {$idleTimeout} seconds");

        if ($isDryRun) {
            $this->warn('DRY RUN MODE - No actual cleanup will be performed');
        }

        // Get all cache keys that match user activity pattern
        // Note: This is a simplified approach. In production, you might want to
        // maintain a separate index of active sessions

        $this->info('Scanning for expired user activity sessions...');

        // Since we can't easily iterate over all cache keys in most cache drivers,
        // we'll use a different approach: clean up based on database sessions if using database driver

        if (Config::get('session.driver') === 'database') {
            $this->cleanDatabaseSessions($isDryRun, $idleTimeout, $currentTime);
        } else {
            $this->info('Cache-based session cleanup requires manual implementation for your cache driver.');
            $this->info('Consider using database sessions for automatic cleanup.');
        }

        // Log the cleanup operation
        if (!$isDryRun && $cleanedCount > 0) {
            AuditTrail::log('session_cleanup', "Cleaned {$cleanedCount} expired sessions");
        }

        $this->info("Session cleanup completed. Cleaned: {$cleanedCount} sessions");

        return Command::SUCCESS;
    }

    /**
     * Clean database sessions
     */
    private function cleanDatabaseSessions($isDryRun, $idleTimeout, $currentTime)
    {
        $sessionTable = Config::get('session.table', 'sessions');
        $cleanedCount = 0;

        try {
            // Get sessions that haven't been updated within the idle timeout period
            $expiredSessions = \DB::table($sessionTable)
                ->where('last_activity', '<', $currentTime - $idleTimeout)
                ->get();

            $this->info("Found {$expiredSessions->count()} expired sessions");

            if (!$isDryRun && $expiredSessions->count() > 0) {
                $cleanedCount = \DB::table($sessionTable)
                    ->where('last_activity', '<', $currentTime - $idleTimeout)
                    ->delete();

                $this->info("Deleted {$cleanedCount} expired database sessions");
            } else if ($isDryRun) {
                $this->info("Would delete {$expiredSessions->count()} expired database sessions");

                // Show some examples
                $examples = $expiredSessions->take(5);
                if ($examples->count() > 0) {
                    $this->info('Examples of sessions that would be deleted:');
                    foreach ($examples as $session) {
                        $lastActivity = date('Y-m-d H:i:s', $session->last_activity);
                        $this->line("  - Session ID: {$session->id}, Last Activity: {$lastActivity}");
                    }
                }
            }

            // Also clean up user activity cache entries
            $this->cleanUserActivityCache($isDryRun, $idleTimeout, $currentTime);
        } catch (\Exception $e) {
            $this->error("Error cleaning database sessions: " . $e->getMessage());
        }
    }

    /**
     * Clean user activity cache entries
     */
    private function cleanUserActivityCache($isDryRun, $idleTimeout, $currentTime)
    {
        $this->info('Cleaning user activity cache entries...');

        // This is a simplified approach - in production you might want to maintain
        // a registry of active user sessions
        try {
            // Get all users and check their activity cache
            $users = \App\Models\User::all();
            $cleanedCacheCount = 0;

            foreach ($users as $user) {
                $sessionKey = 'user_last_activity_' . $user->id;
                $lastActivity = Cache::get($sessionKey);

                if ($lastActivity && ($currentTime - $lastActivity) > $idleTimeout) {
                    if (!$isDryRun) {
                        Cache::forget($sessionKey);
                        $cleanedCacheCount++;
                    } else {
                        $lastActivityDate = date('Y-m-d H:i:s', $lastActivity);
                        $this->line("  - Would clean cache for user {$user->name} (ID: {$user->id}), Last Activity: {$lastActivityDate}");
                        $cleanedCacheCount++;
                    }
                }
            }

            if ($cleanedCacheCount > 0) {
                $action = $isDryRun ? 'Would clean' : 'Cleaned';
                $this->info("{$action} {$cleanedCacheCount} user activity cache entries");
            } else {
                $this->info('No expired user activity cache entries found');
            }
        } catch (\Exception $e) {
            $this->error("Error cleaning user activity cache: " . $e->getMessage());
        }
    }
}
