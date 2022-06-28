<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    // protected $fillable = [
    //     'uuid',
    //     'name',
    //     'email',
    //     'usuario',
    //     'password',
    //     'type_user',
    //     'potho',
    //     'user_state_id ',
    // ];

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

        $url = 'http://localhost:3000/reset-password?token=' . $token . '&email='. $this->email;

        $this->notify(new ResetPasswordNotification($url));
    }
}
