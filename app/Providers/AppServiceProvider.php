<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

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
        // Validator::extend('valid_time_range', function ($attribute, $value, $parameters, $validator) {
        //     $startTime = strtotime('06:00');
        //     $endTime = strtotime('07:15');
        //     $userTime = strtotime($value);
    
        //     return ($userTime >= $startTime && $userTime <= $endTime);
        // });
    }
}
