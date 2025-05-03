<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'qtys' => $this->qty,
            'unit_price_instock' => $this->unit_price_stock,
            'sale_price' => $this->unit_sale_stock,
            'image' => asset('storage/'.$this->image)
        ];
    }
}
