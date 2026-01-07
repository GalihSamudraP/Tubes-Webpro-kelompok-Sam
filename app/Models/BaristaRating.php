<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaristaRating extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'barista_id',
        'rating',
        'review',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function barista()
    {
        return $this->belongsTo(User::class, 'barista_id');
    }
}
