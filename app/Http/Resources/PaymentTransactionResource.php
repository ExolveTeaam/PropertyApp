<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentTransactionResource extends JsonResource
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
        'location' => $this->location,
        'email' => $this->user->email,
        'inspector_name' => $this->inspector->name,
        'inspection_date' => $this->first_date,
        'status' => $this->transaction->status

        ];
    }
}
