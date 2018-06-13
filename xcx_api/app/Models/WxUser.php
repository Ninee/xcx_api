<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WxUser extends Model
{
    protected $fillable = ['appid', 'unionid', 'openid', 'nick', 'avatar'];
}
