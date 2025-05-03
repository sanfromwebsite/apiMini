<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Import extends Model
{
    public $timestamps = true;
    protected $table = 'imports';
    protected $fillable = [
        'id',
        'import_date',
        'staff_id',
        'stuff_name',
        'supplier_id',
        'supplier_name',
        'company',
        'import_total'
    ];

    public function staff(){

        return $this->belongsTo(Staff::class,'staff_id','user_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'id');
    }

    public function products() {
        return $this->belongsToMany(Product::class, 'importdetails', 'import_id', 'product_id')
            ->withPivot('product_name', 'qty', 'unit_price', 'total_product');
    }
    
}
