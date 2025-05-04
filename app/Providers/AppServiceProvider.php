<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use Stevebauman\Location\Facades\Location;
use App\Models\Currency;

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

        if (!auth()->check() && !session()->has('guest_currency')) {
            $location = Location::get(request()->ip());
            $currencyCode = match ($location->countryCode ?? 'US') {
                'SG' => 'SGD',
                'MM' => 'MMK',
                'VN' => 'VND',
                'US' => 'USD',
                default => 'USD'
            };
    
            session()->put('guest_currency', Currency::where('code', $currencyCode)->first());
        }
    }
}
