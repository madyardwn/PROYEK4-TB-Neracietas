<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'description',
        'date',
        'time',
        'location',
        'poster',
        'type',
        'is_active',
    ];

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
