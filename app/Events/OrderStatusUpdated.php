<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Http\Resources\OrderResource;

class OrderStatusUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order->loadMissing(['orderType', 'partnership', 'manager', 'workers']);
    }

    public function broadcastOn(): array
    {
        $channels = [];
        $channels[] = new PrivateChannel('order.' . $this->order->id);
        foreach ($this->order->workers as $worker)
        {
            $channels[] = new PrivateChannel('worker.' . $worker->id);
        }
        return $channels;
    }

    public function broadcastAs(): string
    {
        return 'order.status.updated';
    }

    public function broadcastWith(): array
    {
        return ['order' => new OrderResource($this->order)];
    }
}