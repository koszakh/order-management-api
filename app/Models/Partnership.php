<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property string $name
 * 
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property-read Collection|User[] $users
 * @property-read Collection|Order[] $orders
 */

class Partnership extends Model {
    use HasFactory;
 
    protected $fillable = [
        'name'
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

    public function users() {
        return $this->hasMany(User::class);
    }

    public function orders() {
        return $this->hasMany(Order::class);
    }
}
