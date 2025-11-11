<?php
// AppServiceProvider.php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
// N+1問題の発見用
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

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
        Gate::define('isAdmin',function($user){//←追記
            return $user->role == 'admin';//←追記
        });//←追記
    }
}
