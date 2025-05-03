<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StaffResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'user'=> [
                'id' => $this->user_id,
                'name' => $this->user->name,
                'email' => $this->user->email
            ],  
            'gender' => $this->gender,
            'dob' => $this->dob,
            'salary' => $this->salary,
            'photo' => asset('storage/' . $this->photo),
            'stopWork' => $this->stopWork,
            'position' => [
                'id' => $this->position_id,
                'name' => $this->position->name,
            ]
            //'position' => new PositionResource($this->whenLoaded('position')),
        ];
    }
}
