<?php

namespace App\Providers;

use App\Models\EmailSetting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class MailConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Only apply if table exists and we're not in console (migrations)
        if ($this->app->runningInConsole()) {
            return;
        }

        try {
            if (!Schema::hasTable('email_settings')) {
                return;
            }

            $settings = EmailSetting::getSettings();

            if ($settings && $settings->host) {
                Config::set('mail.default', $settings->mailer);
                Config::set('mail.mailers.smtp.host', $settings->host);
                Config::set('mail.mailers.smtp.port', $settings->port);
                Config::set('mail.mailers.smtp.username', $settings->username);
                Config::set('mail.mailers.smtp.password', $settings->getDecryptedPassword());
                Config::set('mail.mailers.smtp.encryption', $settings->encryption ?: null);
                Config::set('mail.from.address', $settings->from_address);
                Config::set('mail.from.name', $settings->from_name);
            }
        } catch (\Exception $e) {
            // Silently fail if database is not available
        }
    }
}
