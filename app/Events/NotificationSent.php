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
use App\Models\Notifications;

class NotificationSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

	public $notification;
	
    /**
     * Create a new event instance.
     */
    public function __construct(Notifications $notification)
    {
        $this->notification = $notification;
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
            'id'          => $this->notification->id,
            'content'     => $this->notification->content,
            'sender_id'   => $this->notification->sender_id,
            'receiver_id' => $this->notification->receiver_id,
            'url'         => $this->notification->url,
            'sent_at'     => $this->notification->sent_at->toDateTimeString(),
        ];
		
    }
    /**
     * The custom event name to broadcast as.
     */
    public function broadcastAs()
    {
        return 'NotificationSent';
    }
}
