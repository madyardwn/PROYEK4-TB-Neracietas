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
        'image',
        'is_active',
        'cabinet_id',
    ];

    public function cabinet()
    {
        return $this->belongsTo(Cabinet::class);
    }
}
