<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class login_tokens extends Model
{
    protected $fillable = ['user_id','token'];
}
