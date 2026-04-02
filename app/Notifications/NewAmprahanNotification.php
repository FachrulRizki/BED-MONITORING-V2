<?php

namespace App\Notifications;

use App\Models\AmprahanReport;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Notification;

class NewAmprahanNotification extends Notification implements ShouldBroadcast
{
    protected mixed $notifiable = null;

    public function __construct(protected AmprahanReport $report)
    {
        $this->report->loadMissing('room');
    }

    public function via($notifiable): array
    {
        $this->notifiable = $notifiable;

        return ['database', 'broadcast'];
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

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('notifications.' . $this->notifiable?->id);
    }
}
