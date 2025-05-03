<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class customerOrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_date' => $this->order_date,
            'staff' => [
                'id' => $this->staff->user->id,
                'name' => $this->staff->user->name,
                'position' => $this->staff->position->name
            ],
            'customer' => [
                'id' => $this->customer->user->id,
                'name' => $this->customer->user->name,
                'contact' => $this->customer->contact
            ],
            'product' => $this->products->map(function ($product){
                return [
                    'id' => $product->id,
                    'name' => $product->pivot->product_name,
                    'quantity' => $product->pivot->qty,
                    'unit_price' => floatval($product->pivot->unit_price),
                    'total_price' => floatval($product->pivot->total_product),
                ];
            })
        ];
    }
}
