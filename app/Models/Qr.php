<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Qr extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'is_active' => 'boolean',
        'expiration_date' => 'timestamp',
        'user_id' => 'integer',
    ];

    public function qrInformation()
    {
        return $this->hasOne(QrInformation::class);
    }

    public function qrDesign()
    {
        return $this->hasOne(QrDesign::class);
    }

    public function qrVisits()
    {
        return $this->hasOne(QrVisits::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
