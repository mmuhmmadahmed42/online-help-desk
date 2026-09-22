<?php

namespace App\Events;

use App\Models\Ticket;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewTicketNotification implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Ticket $ticket;

    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('pm-notifications'),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->ticket->id,
            'reference_number' => $this->ticket->reference_number,
            'title' => $this->ticket->title,
        ];
    }

    public function broadcastAs(): string
    {
        return 'new-ticket-notification';
    }
}