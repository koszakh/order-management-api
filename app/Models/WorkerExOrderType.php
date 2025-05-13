<?php

namespace App\Models;

use App\States\Assigned;
use App\States\Completed;
use App\States\Created;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\States\OrderState;

/**
 * @property int $id
 * @property int $worker_id
 * @property int $order_type_id
 * 
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property-read Worker $worker
 * @property-read OrderType $type
 */

class WorkerExOrderType extends Model {
    use HasFactory;

    public $table = 'workers_ex_order_types';

    protected $fillable = [
        'worker_id',
        'order_type_id'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at'
    ];
    
    public function type() {
        return $this->belongsTo(OrderType::class, 'order_type_id');
    }
    
    public function worker() {
        return $this->belongsTo(Worker::class, 'worker_id');
    }
}
