<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// N+1問題の発見用
use Illuminate\Database\Eloquent\Model;

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
        // N+1問題の発見用
        Model::preventLazyLoading(!$this->app->isProduction());
    }
}
