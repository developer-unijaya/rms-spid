<?php

namespace DeveloperUnijaya\RmsSpid\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class UserSpid extends Model
{
    protected $table = 'user_spid';

    protected $fillable = [
        'user_id',
        'user_spid_id',
        'redirect_token',
    ];

    protected $casts = [
        'redirect_token_expired_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(config('auth.providers.users.model'));
    }

    public function generateRedirectToken()
    {
        $is_success = false;

        try {

            $redirect_token = Str::uuid()->toString();
            $redirect_token_expired_at = null;

            if (config('rms-spid.redirect_token_validity')) {
                $redirect_token_expired_at = Carbon::now()->addMinutes(config('spid.redirect_token_validity'));
            }

            $this->redirect_token = $redirect_token;
            $this->redirect_token_expired_at = $redirect_token_expired_at;
            $this->save();

            $is_success = true;

        } catch (\Throwable$th) {

            $is_success = false;
        }

        return $is_success;
    }

    public function resetRedirectToken()
    {
        $this->redirect_token = null;
        $this->redirect_token_expired_at = null;
        $this->save();
    }
}
