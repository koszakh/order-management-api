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
 * @property int $partnership_id
 * @property int $user_id
 * @property string $description
 * @property string $address
 * @property float $amount
 * @property OrderState $status
 * 
 * @property Carbon $date
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property-read Collection|User[] $users
 * @property-read Collection|Order[] $orders
 */

class Order extends Model {
    use HasFactory;

    public const STATUSES = ['created', 'assigned', 'completed'];
 
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

    protected $casts = [
        'date' => 'date',
        'status' => OrderState::class
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
    
    public function orderType() {
        return $this->belongsTo(OrderType::class, 'type_id');
    }
    
    public function partnership() {
        return $this->belongsTo(Partnership::class);
    }

    public function manager() {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function workers() {
        return $this->belongsToMany(Worker::class, 'order_worker')->withPivot('amount')->withTimestamps();
    }

    public function assign() {
        $this->status->transitionTo(Assigned::$name);
    }

    public function complete() {
        $this->status->transitionTo(Completed::$name);
    }
}
