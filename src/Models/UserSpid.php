<?php

namespace DeveloperUnijaya\RmsSpid\Models;

use Illuminate\Database\Eloquent\Model;

class UserSpid extends Model
{
    protected $table = 'user_spid';

    protected $fillable = [
        'user_id',
        'user_spid_id',
        'redirect_token',
    ];

    public function user()
    {
        return $this->belongsTo(config('auth.providers.users.model'));
    }
}
