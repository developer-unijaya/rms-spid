<?php

namespace DeveloperUnijaya\RmsSpid\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @property integer $id
 * @property integer $user_id
 * @property string $spid_id
 * @property string $redirect_token
 * @property string $created_at
 * @property string $updated_at
 */
class User extends Authenticatable
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
}
