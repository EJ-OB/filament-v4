<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Model::shouldBeStrict();
        Model::automaticallyEagerLoadRelationships();

        Gate::define('viewPulse', function (User $user) {
            return $user->email === 'test@example.com';
        });
    }
}
