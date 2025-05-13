<?php

namespace App\Models;

use App\States\Assigned;
use App\States\Completed;
use App\States\Created;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\States\OrderState;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $order_id
 * @property int $worker_id
 * @property float $amount
 * 
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property-read Order $order
 * @property-read Worker $worker
 */

class OrderWorker extends Model
{
    use HasFactory;

    public $table = 'order_worker';
 
    protected $fillable = [
        'type_id',
        'partnership_id',
        'user_id',
        'description',
        'date',
        'address',
        'amount',
        'status'
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
    
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function worker(): BelongsTo
    {
        return $this->belongsTo(Worker::class, 'worker_id');
    }
}
