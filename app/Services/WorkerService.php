<?php

namespace App\Services;

use App\Repositories\WorkerRepository;
use Illuminate\Database\Eloquent\Collection;

class WorkerService {
    protected WorkerRepository $workerRepository;

    public function __construct(WorkerRepository $workerRepository) {
        $this->workerRepository = $workerRepository;
    }

    public function getWorkersAvailableForOrderTypes(?array $orderTypeIds): Collection {
        if (empty($orderTypeIds)) {
             return $this->workerRepository->getAllWorkers();
        }
        return $this->workerRepository->findAvailableForOrderTypes($orderTypeIds);
    }
}