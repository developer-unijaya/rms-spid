<?php

namespace DeveloperUnijaya\RmsSpid\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Throwable;

class UserSpid extends Model
{
    protected $table = 'user_spid';

    protected $fillable = [
        'user_id',
        'user_spid_id',
        'src',
        'reg_type',
        'reg_json',
    ];

    protected $casts = [
        'redirect_token_expired_at' => 'datetime',
        'reg_approve_at' => 'datetime',
        'reg_reject_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
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
                $redirect_token_expired_at = Carbon::now(config('rms-spid.timezone'))->addMinutes(config('rms-spid.redirect_token_validity'));
            }

            $this->redirect_token = $redirect_token;
            $this->redirect_token_expired_at = $redirect_token_expired_at;
            $this->save();

            $is_success = true;

        } catch (Throwable $th) {

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

    public function appendLog($logs = [])
    {
        try {
            if (config('rms-spid.enable_log')) {
                $userSpidLog = $this->log;

                if (!$userSpidLog) {
                    $userSpidLog = [];
                } else {
                    $userSpidLog = json_decode($this->log, true);
                }

                foreach ($logs as $key => $log) {
                    $userSpidLog[Carbon::now(config('rms-spid.timezone'))->format("Y-m-d H:i:s.u")] = $log;
                }

                $logJson = json_encode($userSpidLog);

                $this->log = $logJson;
                $this->save();
            }

        } catch (Throwable $th) {
            //throw $th;
        }
    }

    public function userActive()
    {
        $isActive = false;

        $user = $this->user;
        if ($user) {
            $userFillables = $user->getFillable();

            if (in_array('is_active', $userFillables)) {
                $isActive = $user->is_active;
            }

            if (in_array('status', $userFillables)) {
                $status = $user->status;
                if ($status == 'aktif') {
                    $isActive = true;
                }
            }
        }

        return $isActive;
    }
}
