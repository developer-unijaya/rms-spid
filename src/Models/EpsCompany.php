<?php

namespace DeveloperUnijaya\RmsSpid\Models;

use Illuminate\Database\Eloquent\Model;

class EpsCompany extends Model
{
    protected $table = 'consultancy_company';

    protected $fillable = [
        'company_name',
        'company_reg_no',
        'company_addrs_1',
        'company_addrs_2',
    ];

    protected $casts = [
    ];

}
