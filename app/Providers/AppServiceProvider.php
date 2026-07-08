<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {


        if (config('app.env') !== 'local') {
            URL::forceScheme('https');
            URL::forceRootUrl(config('app.url'));
            
            Livewire::setScriptRoute(function ($handle) {
                return Route::get('/lead-up-review/livewire/livewire.js', $handle);
            });

            Livewire::setUpdateRoute(function ($handle) {
                return Route::post('/lead-up-review/livewire/update', $handle);
            });

        }
    }
}
