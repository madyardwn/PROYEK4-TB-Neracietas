<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Filosofy extends Model
{
    use HasFactory;

    protected $table = 'filosofy';

    protected $fillable = [
        'cabinet_id',
        'logo',
        'label',
    ];

    public function cabinet()
    {
        return $this->belongsTo(Cabinet::class);
    }
}
