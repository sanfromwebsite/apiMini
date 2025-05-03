<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_date' =>Carbon::parse($this->order_date)->format('Y-m-d H:i:s'),
            'staff' => [
                'user' => [
                    'id' => $this->staff->user->id,
                    'name' => $this->staff->user->name,
                ],
                'gender' => $this->staff->gender,
                'photo' => asset('storage/' . $this->staff->photo),
                'position' => [
                    'id' => $this->staff->position->id,
                    'name' => $this->staff->position->name
                ]
            ],
            'customer' => [
                'user' => [
                    'id' => $this->customer->user->id,
                    'name' => $this->customer->user->name
                ],
                'contact' => $this->customer->contact
            ],
            'products' => $this->products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->pivot->product_name,
                    'quantity' => $product->pivot->qty,
                    'unit_price' => floatval($product->pivot->unit_price),
                    'total_price' => floatval($product->pivot->total_product),
                ];
            }),
            'total' => floatval($this->total),
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::parse($this->updated_at)->format('Y-m-d H:i:s')
        ];

    }
}
