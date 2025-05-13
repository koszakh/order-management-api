<?php

namespace App\Repositories;

use App\Models\Worker;
use Illuminate\Database\Eloquent\Collection;

class WorkerRepository {
    public function findAvailableForOrderTypes(array $orderTypeIds): Collection {
        if (empty($orderTypeIds)) {
            return Worker::all();
        }

        return Worker::where(function ($query) use ($orderTypeIds) {
            foreach ($orderTypeIds as $typeId) {
                $query->orWhereDoesntHave('excludedOrderTypes', function ($subQuery) use ($typeId) {
                    $subQuery->where('order_types.id', $typeId);
                });
            }
        })->get();
    }

    public function getAllWorkers(): Collection {
        return Worker::all();
    }
}