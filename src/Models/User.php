<?php

namespace DeveloperUnijaya\RmsSpid\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'users';

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
