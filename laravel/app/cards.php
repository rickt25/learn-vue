<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class cards extends Model
{
    protected $fillable = ['list_id','order','task'];
}
