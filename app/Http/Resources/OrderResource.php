<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Order;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if (!$this->resource) {
            return [];
        }

        /** @var Order $this */

        return [
            'id' => $this->id,
            'description' => $this->description,
            'date' => $this->date->format('Y-m-d'),
            'address' => $this->address,
            'amount' => (float) $this->amount,
            'status' => $this->status,
            'type' => new OrderTypeResource($this->whenLoaded('orderType')),
            'partnership' => new PartnershipResource($this->whenLoaded('partnership')),
            'manager' => new UserResource($this->whenLoaded('manager')),
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
            'workers' => WorkerResource::collection($this->whenLoaded('workers')),
        ];
    }
}
