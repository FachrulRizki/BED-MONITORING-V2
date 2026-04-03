<?php

namespace App\Events;

use App\Models\AmprahanReport;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AmprahanNotificationCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public User $user,
        public AmprahanReport $report,
    ) {
        $this->report->loadMissing('room');
    }

    public function broadcastOn(): array
    {
        return [new PrivateChannel('notifications.'.$this->user->id)];
    }

    public function broadcastAs(): string
    {
        return 'amprahan.notification.created';
    }

    public function broadcastWith(): array
    {
        return [
            'room_name' => $this->report->room->name ?? '',
            'report_time' => $this->report->report_time,
            'report_id' => $this->report->id,
            'link' => route('amprahans.show', $this->report->id),
        ];
    }
}
