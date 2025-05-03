<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    public $timestamps = true;
    protected $table = 'staff';
    protected $fillable = ['gender', 'dob', 'position_id','salary','photo','stopWork'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function position(){
        return $this->belongsTo(Position::class,'position_id','id');
    }

    public function imports(){
        return $this->hasMany(Import::class,'staff_id','id');
    }

    public function orders(){
        return $this->hasMany(Order::class,'staff_id','id');
    }

}
