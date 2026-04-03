<?php

namespace App\Notifications;

use App\Models\AmprahanReport;
use Illuminate\Notifications\Notification;

class NewAmprahanNotification extends Notification
{
    public function __construct(protected AmprahanReport $report)
    {
        $this->report->loadMissing('room');
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'room_name'   => $this->report->room->name ?? '',
            'report_time' => $this->report->report_time,
            'report_id'   => $this->report->id,
            'link'        => route('amprahans.show', $this->report->id),
        ];
    }
}
