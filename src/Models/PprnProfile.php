<?php

namespace DeveloperUnijaya\RmsSpid\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Throwable;

class PprnProfile extends Model
{
    protected $table = 'profiles';

    protected $fillable = [
        'user_id',
        'nric',
        'institute_id',
        'uni',
        'designation_id',
        'position',
        'mobile_no',
        'office_no',
        'cv',
        'mail_address',
        'academic',
        'expertises',
    ];

    protected $casts = [
        
    ];

    public function user()
    {
        return $this->belongsTo(config('auth.providers.users.model'));
    }
}