<?php

namespace App\Services;

use App\Models\AmprahanReport;
use App\Models\User;
use App\Notifications\NewAmprahanNotification;
use Illuminate\Support\Facades\Notification;

class AmprahanNotificationService
{
    /**
     * Send notifications for a new amprahan report to all users.
     */
    public function notify(AmprahanReport $report): void
    {
        $users = User::all();
        Notification::send($users, new NewAmprahanNotification($report));
    }
}
