<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    public $timestamps = false;
    protected $table = 'positions';
    protected $fillable = ['name'];

    public function staffs(){

        return $this->hasMany(Staff::class,'position_id','id');
    }

}
