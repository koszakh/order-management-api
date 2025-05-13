<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderService;
use App\States\OrderState;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public OrderService $orderService;
    public function index(Request $request, OrderService $orderService)
    {
        $workerId = 1;
        $orderTypeId = 11;
        $order = $orderService->createOrder()

        $orderService->assignWorkerToOrder()

        return ['a' => 123];
    }
}
