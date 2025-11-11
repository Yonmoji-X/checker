<?php
// EventServiceProvider.php
namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        // 'App\Events\EventName' => [
        //     'App\Listeners\EventListener',
        // ],
    ];

    public function boot(): void
    {
        //
    }
}
