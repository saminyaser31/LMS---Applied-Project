<?php

namespace App\Providers;

use App\Models\Teacher\Teachers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class TeacherServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('teacher', function ($app) {
            return Teachers::where('user_id', Auth::id())->first();
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
