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
 * @property-read OrderType $type
 * @property-read User $manager
 * @property-read Partnership $partnership
 * @property-read Collection|User[] $workers
 */

class Order extends Model
{
    use HasFactory;

    public $table = 'orders';

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
    
    public function type()
    {
        return $this->belongsTo(OrderType::class, 'type_id');
    }
    
    public function partnership()
    {
        return $this->belongsTo(Partnership::class, 'partnership_id');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function workers()
    {
        return $this->belongsToMany(Worker::class, 'order_worker')->withPivot('amount')->withTimestamps();
    }

    public function assign()
    {
        $this->status->transitionTo(Assigned::$name);
    }

    public function complete()
    {
        $this->status->transitionTo(Completed::$name);
    }
}
