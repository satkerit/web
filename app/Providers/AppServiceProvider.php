<?php

namespace App\Providers;

use App\Helpers\HtmlSanitizer;
use App\Models\SmtpSetting;
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
    $this->app->bind('path.public', function() {
        // Mengarahkan path public ke folder public_html
        return realpath(base_path().'/../public_html');
    });
}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->loadSmtpSettings();
        $this->registerBladeDirectives();
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
