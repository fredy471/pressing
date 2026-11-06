<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaymentStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public int $userId,
        public string $reference,
        public string $status,
        public ?string $redirectUrl = null,
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('payments.' . $this->userId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'payment.status.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'reference' => $this->reference,
            'status' => $this->status,
            'redirect_url' => $this->redirectUrl,
        ];
    }
}