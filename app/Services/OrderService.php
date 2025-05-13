<?php

namespace App\Services;

use App\Repositories\OrderRepository;
use App\Models\User; // Менеджер
use App\Models\Order;
use App\Models\Worker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
use App\Events\OrderStatusUpdated;
use App\States\Assigned;
use App\States\Created;

class OrderService {

    protected OrderRepository $orderRepository;

    public function __construct(OrderRepository $orderRepository) {
        $this->orderRepository = $orderRepository;
    }

    public function createOrder(array $data): Order {
        $manager = Auth::user();
        $data['user_id'] = $manager->id;
        
        if (empty($data['partnership_id']) && $manager->partnership_id) {
            $data['partnership_id'] = $manager->partnership_id;
        } elseif (empty($data['partnership_id'])) {
            throw new InvalidArgumentException('Company ID (partnership_id) is required to create an order.');
        }

        $data['status'] = Created::$name;
        return $this->orderRepository->create($data);
    }

    public function assignWorkerToOrder(Order $order, int $workerId, float $amount): Order {
        $worker = Worker::findOrFail($workerId);

        $isExcluded = $worker->excludedOrderTypes()->where('order_type_id', $order->type_id)->exists();
        if ($isExcluded) {
            throw ValidationException::withMessages([
                'worker_id' => "The worker {$worker->surname} {$worker->name} does not fulfill orders of the type: '{$order->type->name}'.",
            ]);
        }

        if ($order->workers()->where('workers.id', $workerId)->exists()) {
            throw ValidationException::withMessages([
                'worker_id' => 'This performer has already been assigned to this order.',
            ]);
        }

        $this->orderRepository->assignWorker($order, $worker, $amount);

        if ($order->status == Created::$name) {
            $this->orderRepository->updateStatus($order, Assigned::$name);
        }
        return $order->load('workers');
    }

    public function updateOrderStatus(Order $order, string $newStatus): Order {
        $oldStatus = $order->status;
        if ($this->orderRepository->updateStatus($order, $newStatus)) {
            $order->refresh();
            if ($oldStatus !== $newStatus) {
                event(new OrderStatusUpdated($order));
            }
        }
        return $order;
    }
}