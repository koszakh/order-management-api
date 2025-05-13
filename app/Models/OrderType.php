<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 */

class OrderType extends Model
{
    use HasFactory;

    public $table = 'order_types';
 
    protected $fillable = [
        'name'
    ];
    
    public $timestamps = false;
}
