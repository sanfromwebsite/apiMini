<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImportdetailController extends Controller
{
    protected $table = 'importdetails';
    protected $fillable = [
        'product_name',
        'qty',
        'unit_price',
        'total_product'
    ];
}
