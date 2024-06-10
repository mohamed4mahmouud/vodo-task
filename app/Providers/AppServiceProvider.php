<?php

namespace App\Providers;

use App\Models\Note;
use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

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
        Gate::define('view-update-delete' , function (User $user, Note $note){
            return $user->id === $note->user_id;
        });
    }
}
