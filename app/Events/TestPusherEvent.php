<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TestPusherEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    /**
     * Create a new event instance.
     */
    public function __construct($message)
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
        \Log::info('🔔 TestPusherEvent: broadcastOn() called', [
            'channels' => ['private-admin-notifications']
        ]);
        
        return [
            new PrivateChannel('admin-notifications'),
        ];
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith(): array
    {
        \Log::info('🔔 TestPusherEvent: broadcastWith() called', [
            'message' => $this->message
        ]);
        
        return [
            'message' => $this->message,
            'timestamp' => now()->toISOString(),
        ];
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs(): string
    {
        \Log::info('🔔 TestPusherEvent: broadcastAs() called', [
            'event_name' => 'TestPusherEvent'
        ]);
        
        return 'TestPusherEvent';
    }
}