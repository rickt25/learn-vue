<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class board_members extends Model
{
    protected $fillable = ['board_id','user_id'];
}
