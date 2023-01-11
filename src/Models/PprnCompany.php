<?php

namespace DeveloperUnijaya\RmsSpid\Models;

use Illuminate\Database\Eloquent\Model;

class PprnCompany extends Model
{
    protected $table = 'companies';

    protected $fillable = [
        'pic_id',
        'is_completed',
        'name',
        'mail_address',
        'mycoid',
        'mycoid_file',
        'bpr_no',
        'desc',
        'employees',
        'sales',
        'size_of_company',
        'industry',
        'business_ownership',
        'future_state',
        'bmc',
        'is_assess',
        'status',
    ];

    protected $casts = [
    ];

}
