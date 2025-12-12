<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventTypeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->EventTypeId,
            'code' => $this->EventTypeCode,
            'name' => $this->EventTypeName,
            'created_by' => $this->CreatedBy,
            'created_at' => $this->CreatedAt ? $this->CreatedAt->toDateTimeString() : null,
            'modified_by' => $this->ModifiedBy,
            'modified_at' => $this->ModifiedAt ? $this->ModifiedAt->toDateTimeString() : null,
            'deleted' => (bool) $this->DeleteFlag,
        ];
    }
}