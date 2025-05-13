<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\WorkerService;
use App\Http\Resources\WorkerResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class WorkerController extends Controller
{
    protected WorkerService $workerService;

    public function __construct(WorkerService $workerService)
    {
        $this->workerService = $workerService;
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        $validated = $request->validate([
            'order_types' => 'nullable|array',
            'order_types.*' => 'integer|exists:order_types,id',
        ]);
        
        $orderTypeIds = $validated['order_types'] ?? [];
        $workers = $this->workerService->getWorkersAvailableForOrderTypes($orderTypeIds);
        return WorkerResource::collection($workers);
    }
}