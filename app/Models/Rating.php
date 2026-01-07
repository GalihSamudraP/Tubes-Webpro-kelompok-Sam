<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'rating',
        'review',
        'type',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
