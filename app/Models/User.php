<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'otp',
        'otp_expires_at',
        'invitation_code',
        'invited_by',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'otp',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'otp_expires_at' => 'datetime',
    ];

    // app/Models/User.php

public function isSuperAdmin()
{
    return $this->role === 'super_admin';
}

public function isAdmin()
{
    return $this->role === 'admin';
}

   // User who invited this user
    public function invitedBy()
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    // Alias method (optional, same as invitedBy)
    public function inviter()
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    // Direct users invited by this user
    public function directSubordinates()
    {
        return $this->hasMany(User::class, 'invited_by');
    }

    // Recursive team subordinates (all levels)
    public function getRecursiveTeamSubordinates()
    {
        $team = collect();

        foreach ($this->directSubordinates as $subordinate) {
            $team->push($subordinate);
            $team = $team->merge($subordinate->getRecursiveTeamSubordinates());
        }

        return $team;
    }
    public function leaderRequest()
    {
        return $this->hasOne(LeaderRequest::class);
    }


    // Optional: HasManyThrough — Not recursive, for deeper access if needed
    public function teamSubordinates()
    {
        return $this->hasManyThrough(
            User::class,
            User::class,
            'invited_by',
            'invited_by',
            'id',
            'id'
        );
    }

}
