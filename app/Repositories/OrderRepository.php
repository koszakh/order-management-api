<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\Worker;
use App\States\Assigned;
use App\States\Completed;

class OrderRepository {
    public function create(array $data): Order {
        return Order::create($data);
    }

    public function findById(int $orderId): ?Order {
        return Order::find($orderId);
    }

    public function assignWorker(Order $order, Worker $worker, float $amount): void {
        $order->workers()->attach($worker->id, ['amount' => $amount]);
    }

    public function updateStatus(Order $order, string $status): bool {
        if ($status == Assigned::$name) {
            $order->assign();
        } elseif ($status == Completed::$name) {
            $order->complete();
        }
        
        return $order->save();
    }
}