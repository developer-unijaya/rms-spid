<?php

namespace DeveloperUnijaya\RmsSpid\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $user_id
 * @property string $spid_id
 * @property string $redirect_token
 * @property string $created_at
 * @property string $updated_at
 */
class UserSpidToken extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'user_spid_token';
}
