<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Blade;
use App\Helpers\CurrencyHelper;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::directive('currency', function ($expression) {
            return "<?php echo \App\Helpers\CurrencyHelper::format($expression); ?>";
        });

        Blade::directive('currencyDual', function ($expression) {
            return "<?php echo \App\Helpers\CurrencyHelper::formatDual($expression); ?>";
        });

        Blade::directive('currencySymbol', function () {
            return "<?php echo \App\Helpers\CurrencyHelper::getSymbol(); ?>";
        });

        Blade::directive('exchangeRate', function () {
            return "<?php echo \App\Helpers\CurrencyHelper::getExchangeRate(); ?>";
        });
    }
}
