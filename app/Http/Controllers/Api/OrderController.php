<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssignWorkerToOrderRequest;
use App\Services\OrderService;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Resources\OrderResource; // Создадим этот ресурс
use Illuminate\Http\Request;
use App\Models\Order;
use App\Http\Requests\UpdateOrderStatusRequest;

class OrderController extends Controller {
    protected OrderService $orderService;

    public function __construct(OrderService $orderService) {
        $this->orderService = $orderService;
    }

    public function store(StoreOrderRequest $request): OrderResource {
        $order = $this->orderService->createOrder($request->validated());
        return new OrderResource($order->loadMissing(['orderType', 'partnership', 'manager']));
    }

    public function assignWorker(AssignWorkerToOrderRequest $request, Order $order): OrderResource {
        $updatedOrder = $this->orderService->assignWorkerToOrder(
            $order,
            $request->validated()['worker_id'],
            $request->validated()['amount']
        );
        
        return new OrderResource($updatedOrder->loadMissing(['orderType', 'partnership', 'manager', 'workers']));
    }

    public function updateStatus(UpdateOrderStatusRequest $request, Order $order): OrderResource {
        $updatedOrder = $this->orderService->updateOrderStatus($order, $request->validated()['status']);
        return new OrderResource($updatedOrder->loadMissing(['orderType', 'partnership', 'manager', 'workers']));
    }
}