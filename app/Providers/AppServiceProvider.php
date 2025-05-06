<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use Stevebauman\Location\Facades\Location;
use App\Models\Currency;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

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
        Inertia::share([
            'app' => [
                'name' => config('app.name'),
            ],
        ]);

        // Safely check database availability and table
        if (
            $this->app->runningInConsole() === false &&
            !auth()->check() &&
            !Session::has('guest_currency') &&
            $this->databaseReady()
        ) {
            $location = Location::get(request()->ip());
            $currencyCode = match ($location->countryCode ?? 'US') {
                'SG' => 'SGD',
                'MM' => 'MMK',
                'VN' => 'VND',
                'US' => 'USD',
                default => 'USD'
            };

            $currency = Currency::where('code', $currencyCode)->first();

            if (!$currency) {
                $currency = Currency::create([
                    'code' => 'USD',
                    'name' => 'US Dollar',
                    'symbol' => '$',
                    'exchange_rate' => 1,
                    'is_default' => true
                ]);
            }

            Session::put('guest_currency', $currency);
        }
    }

    protected function databaseReady(): bool
    {
        try {
            return Schema::hasTable('currencies');
        } catch (\Throwable $e) {
            return false;
        }
    }
}
