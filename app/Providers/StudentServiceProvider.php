<?php

namespace App\Providers;

use App\Models\Student\Students;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class StudentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('student', function ($app) {
            return Students::where('user_id', Auth::id())->first();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
