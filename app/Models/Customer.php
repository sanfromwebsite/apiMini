<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $table = 'customers';
    protected $fillable = [
        'name',
        'contact'
    ];

    public function orders(){
        return $this->hasMany(Order::class,'customer_id','id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
}
