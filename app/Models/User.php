<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'nim',
        'na',
        'nama_bagus',
        'year',
        'department_id',
        'is_active',
        'device_token'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function departments()
    {
        return $this->belongsToMany(Department::class, 'periodes', 'user_id', 'department_id');
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }
    
    public function periode()
    {
        return $this->hasMany(Periode::class);
    }

    public function notifications()
    {
        return $this->belongsToMany(Notification::class, 'users_notifications', 'user_id', 'notification_id');
    }
}
