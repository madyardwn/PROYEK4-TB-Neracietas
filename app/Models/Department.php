<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'logo',
        'short_name',
        'description',
        'is_active',
    ];

    public function cabinet()
    {
        return $this->belongsTo(Cabinet::class);
    }

    public function programs()
    {
        return $this->hasMany(Program::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'periodes', 'department_id', 'user_id');
    }
}
