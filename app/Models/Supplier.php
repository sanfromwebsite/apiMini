<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'suppliers';
    protected $fillable = ['name', 'address', 'company'];

    public function imports(){
        return $this->hasMany(Import::class,'supplier_id','id');
    }
}
