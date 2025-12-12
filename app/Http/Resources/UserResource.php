<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'UserId' => $this->UserId,
            'UserCode' => $this->UserCode,
            'UserName' => $this->UserName,
            'Email' => $this->Email,
            'PhNumber' => $this->PhNumber,
            'ProfileImg' => $this->ProfileImg,
            'CreatedBy' => $this->CreatedBy,
            'CreatedAt' => $this->CreatedAt,
            'ModifiedBy' => $this->ModifiedBy,
            'ModifiedAt' => $this->ModifiedAt,
            'DeleteFlag' => $this->DeleteFlag,
        ];
    }
}