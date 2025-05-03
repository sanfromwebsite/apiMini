<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class ImportResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'import_date' => Carbon::parse($this->import_date)->format('Y-m-d'),
            'import_total' => floatval($this->import_total),
            'staff' => [
                'id' => $this->staff->user_id,
                'name' => $this->staff->user->name,
                'gender' => $this->staff->gender,
                'photo' => asset('storage/' . $this->staff->photo),
                'position' =>[
                    'id' => $this->staff->position->id,
                    'name' => $this->staff->position->name
                ]
            ],
            'supplier' => [
                'id' => $this->supplier_id,
                'name' => $this->supplier->name,
                'company' => $this->supplier->company,
            ],
            // 'products' => $this->products->map(function ($product) {
            //     return [
            //         'id' => $product->id,
            //         'name' => $product->pivot->product_name,
            //         'quantity' => $product->pivot->qty,
            //         'unit_price' => floatval($product->pivot->unit_price),
            //         'total_price' => $product->pivot->total_product,
            //     ];
            // }),
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::parse($this->updated_at)->format('Y-m-d H:i:s'),
        ];
    }
}
