<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Friend;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('*', function ($view) {
        $userId = session('id');

        $pendingCount = Friend::where('id_user_friended', $userId)
            ->where('status', 0)
            ->count();

        $view->with('pendingCount', $pendingCount);
    });
    }
}
