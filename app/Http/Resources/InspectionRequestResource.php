<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InspectionRequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'owner_name' => $this->user->name,
            'status' => $this->status,
            'location' => $this->location,
            'inspection_date' => $this->first_date,
            'inspectors_name' => $this->inspector->name
        ];
    }
}
