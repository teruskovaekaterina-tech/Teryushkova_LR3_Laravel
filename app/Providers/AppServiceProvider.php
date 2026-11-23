<?php

namespace App\Providers;

use App\Models\Laureate;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        Gate::define('manage-laureate', fn (User $user, Laureate $laureate) =>
            $user->is_admin || $laureate->user_id === $user->id
        );

        Gate::define('restore-laureate', fn (User $user) => $user->is_admin);

        Gate::define('force-delete-laureate', fn (User $user) => $user->is_admin);
    }
}
