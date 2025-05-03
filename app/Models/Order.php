<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public $timestamps = true;
    protected $table = 'orders';
    protected $fillable = [
        'id',
        'order_date',
        'staff_id',
        'customer_id',
        'customer_name',
        'company',
        'order_total'
    ];

    public function staff(){
        return $this->belongsTo(Staff::class,'staff_id','user_id');
    }

    public function customer(){
        return $this->belongsTo(Customer::class, 'customer_id','user_id');
    }

    public function products(){
        return $this->belongsToMany(Product::class, 'order_details','order_id','product_id')
        ->withPivot('product_name', 'qty', 'unit_price', 'total_price');
    }


}
