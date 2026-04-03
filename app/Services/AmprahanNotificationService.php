<?php

namespace App\Services;

use App\Events\AmprahanNotificationCreated;
use App\Models\AmprahanReport;
use App\Models\User;
use App\Notifications\NewAmprahanNotification;

class AmprahanNotificationService
{
    /**
     * Send notifications for a new amprahan report to all users.
     */
    public function notify(AmprahanReport $report): void
    {
        $users = User::all();

        foreach ($users as $user) {
            $user->notify(new NewAmprahanNotification($report));

            event(new AmprahanNotificationCreated($user, $report));
        }
    }
}
