<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'body',
        'link',
        'poster',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'users_notifications', 'notification_id', 'user_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
