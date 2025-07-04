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
use App\Models\Community;

class CommunityCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
	
	public $community;
	
    /**
     * Create a new event instance.
     */
    public function __construct(Community $community)
    {
        $this->community = $community;
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
            'id' => $this->community->id,
            'complaint' => $this->community->complaint,
            'comment' => $this->community->comment,
            'user' => $this->community->user?->name ?? 'Unknown',
            'created_at_human' => $this->community->created_at->diffForHumans(),
            'admin_reply' => $this->community->admin_reply,
            'developer_reply' => $this->community->developer_reply,
        ];
    }
    /**
     * The custom event name to broadcast as.
     */
    public function broadcastAs()
    {
        return 'CommunityCreated';
    }
}
