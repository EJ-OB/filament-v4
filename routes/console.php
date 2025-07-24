<?php

use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $hasManyNotifications = DB::table('notifications')->count() >= 25;

    if ($hasManyNotifications) {
        DB::table('notifications')->truncate();
    }

    User::firstOrFail()->notify(
        Notification::make('inspiring')
            ->info()
            ->title('Your daily inspiration')
            ->body(Inspiring::quotes()->random())
            ->toDatabase()
    );
})
    ->purpose('Display an inspiring quote')
    ->everyMinute();
