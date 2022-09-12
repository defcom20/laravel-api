<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'type_user' => 'integer',
        'user_state_id' => 'integer',
        'email_verified_at' => 'timestamp',
    ];

    public function userState()
    {
        return $this->belongsTo(UserState::class);
    }

    public function sendPasswordResetNotification($token)
    {
        $url = env('URL_FROND').'reset-password?token=' . $token . '&email='. $this->email;
        $this->notify(new ResetPasswordNotification($url));
    }
}
