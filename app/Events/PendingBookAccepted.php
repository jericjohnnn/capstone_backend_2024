<?php

namespace App\Events;

use App\Models\User;
use App\Notifications\NotificationStore;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PendingBookAccepted implements ShouldBroadcast, ShouldQueue
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected User $userToBeNotified;
    protected $user;
    protected string $message;

    /**
     * Create a new event instance.
     */
    public function __construct($user, $userToBeNotified)
    {
        $this->user = $user;
        $this->userToBeNotified = $userToBeNotified;
        $this->message = $this->user->first_name . ' ' . $this->user->last_name . ' accepted your request.';
        $this->storeNotification($this->message);
    }

    protected function storeNotification($message)
    {
        $this->userToBeNotified->notify(new NotificationStore($message));
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->userToBeNotified->id),
        ];
    }

    /**
     * Get the data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'message' => $this->message,
        ];
    }
}
