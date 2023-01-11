<?php

namespace DeveloperUnijaya\RmsSpid\Models;

use Illuminate\Database\Eloquent\Model;

class EpsCompanyUser extends Model
{
    protected $table = 'consultancy_company_user';

    protected $fillable = [
        'consultancy_company_id',
        'users_id',
    ];

    protected $casts = [
    ];

}
