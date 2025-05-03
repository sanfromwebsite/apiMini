<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Importdetail extends Model
{
    protected $table = 'importdetails';
    protected $fillable = [
        'product_name',
        'qty',
        'unit_price',
        'total_product'
    ];
}
