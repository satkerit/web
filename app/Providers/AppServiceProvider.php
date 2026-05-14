<?php

namespace App\Providers;

use App\Helpers\HtmlSanitizer;
use App\Models\SmtpSetting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Check if running on shared hosting with public_html
        $publicHtmlPath = base_path() . '/../public_html';

        if (file_exists($publicHtmlPath)) {
            $this->app->bind('path.public', function () use ($publicHtmlPath) {
                return realpath($publicHtmlPath);
            });
        }

        if ($this->app->environment('local') && class_exists(\Laravel\Telescope\TelescopeServiceProvider::class)) {
            $this->app->register(\App\Providers\TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::shouldBeStrict(! $this->app->isProduction());
        
        // Prevent lazy loading in development
        if (! $this->app->isProduction()) {
            Model::preventLazyLoading();
        }

        $this->loadSmtpSettings();
        $this->registerBladeDirectives();

        // Share CSP nonce with all views
        view()->composer('*', function ($view) {
            $view->with('nonce', request()->attributes->get('csp_nonce'));
        });
    }

    /**
     * Register custom Blade directives
     */
    protected function registerBladeDirectives(): void
    {
        // @cleanHtml($content) - Sanitize HTML content from WYSIWYG editors
        Blade::directive('cleanHtml', function ($expression) {
            return "<?php echo \App\Helpers\HtmlSanitizer::clean($expression); ?>";
        });

        // @compressedImage($path, $quality) - Get compressed image URL
        Blade::directive('compressedImage', function ($expression) {
            return "<?php echo \Illuminate\Support\Facades\Storage::url(\App\Services\ImageCompressionService::compressForWeb($expression)); ?>";
        });

        // @storageUrl($path) - Get storage URL (works in both dev and production)
        Blade::directive('storageUrl', function ($expression) {
            return "<?php echo \App\Helpers\StorageHelper::url($expression); ?>";
        });

        // @assetUrl($path) - Get asset URL (for CSS, JS, images in public folder)
        Blade::directive('assetUrl', function ($expression) {
            return "<?php echo \App\Helpers\StorageHelper::asset($expression); ?>";
        });
    }

    /**
     * Load SMTP settings from database
     */
    protected function loadSmtpSettings(): void
    {
        try {
            if (Schema::hasTable('smtp_settings')) {
                $settings = SmtpSetting::getActive();
                if ($settings) {
                    $settings->applyToConfig();
                }
            }
        } catch (\Exception $e) {
            // Silently fail if database is not available
        }
    }
}
