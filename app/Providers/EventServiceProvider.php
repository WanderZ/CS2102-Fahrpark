<?php

namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\SomeEvent' => [
            'App\Listeners\EventListener',
        ],
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        \Event::listen('router.matched', function ($route, $request) {
          // Keep track of user last activity/login time
          $user = \Auth::user();
          if ($user) {
            \App\Models\User::doUpdate(['id' => $user->id], ['lastLogin' => date("Y-m-d H:i:s")]);
          }
        });
    }
}
