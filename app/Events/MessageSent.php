<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Messages;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
	
	public $message;
    /**
     * Create a new event instance.
     */
    public function __construct(Messages $message)
    {
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
         return [new Channel('my-channel')];
    }
	
	public function broadcastWith()
    {
        return [
            'id'         => $this->message->id,
            'message'    => $this->message->message,
            'sender_id'  => $this->message->sender_id,
            'receiver_id'=> $this->message->receiver_id,
            'sent_at'    => $this->message->sent_at->toDateTimeString(),
        ];
    }

    public function broadcastAs()
    {
        return 'MessageSent';
    }
}
