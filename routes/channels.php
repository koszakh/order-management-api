<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Order;

Broadcast::channel('order.{orderId}', function ($user, $orderId) {
    $order = Order::find($orderId);
    return (int) $user->id === (int) $order?->user_id;
});

Broadcast::channel('worker.{workerId}', function ($user, $workerId) {
    $workerExistsOnManagersOrder = Order::where('user_id', $user->id)
                                  ->whereHas('workers', function ($query) use ($workerId) {
                                      $query->where('workers.id', $workerId);
                                  })->exists();
    return $workerExistsOnManagersOrder;
});

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
