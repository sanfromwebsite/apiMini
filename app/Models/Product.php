<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public $timestaps = false;
    protected $table = 'products';
    protected $fillable = ['id','name','qty','unit_price_stock','unit_sale_stock','image'];

    public function imports(){
        return $this->belongsToMany(Import::class,'importdetails','product_id','import_id')
        ->withPivot('product_name','qty','unit_price','total_product');
    }

    public function orders(){
        return $this->belongsToMany(Order::class,'order_details','product_id','order_id')
        ->withPivot('product_name','qty','unit_price','total_product');
    }
}
