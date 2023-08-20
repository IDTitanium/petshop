<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read string $first_name
 * @property-read string $last_name
 * @property-read string $emal
 * @property-read string $address
 * @property-read string $phone_number
 */
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'address' => $this->address,
            'phone_number' => $this->phone_number
        ];
    }
}
