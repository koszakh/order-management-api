<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $surname
 * @property string $phone
 * 
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property-read Collection|Order[] $orders
 * @property-read Collection|OrderType[] $excludedOrderTypes
 */

class Worker extends Model
{
    use HasFactory;

    public $table = 'workers';
 
    protected $fillable = [
        'name',
        'surname',
        'phone'
    ];

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_worker')->withPivot('amount')->withTimestamps();
    }

    public function excludedOrderTypes()
    {
        return $this->belongsToMany(OrderType::class, 'workers_ex_order_types', 'worker_id', 'order_type_id')
            ->withTimestamps();
    }
}
