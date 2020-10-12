<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class boards extends Model
{
    protected $fillable = ['name', 'creator_id'];
}
