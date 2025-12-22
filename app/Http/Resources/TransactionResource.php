<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'TransactionId' => $this->TransactionId,
            'TransactionCode' => $this->TransactionCode,
            'UserId' => $this->UserId,
            'Email' => $this->Email,
            'Status' => (bool) $this->Status,
            'PaymentType' => $this->PaymentType,
            'TotalAmount' => $this->TotalAmount,
            'TransactionDate' => $this->TransactionDate?->toDateTimeString(),
            'CreatedBy' => $this->CreatedBy,
            'CreatedAt' => $this->CreatedAt?->toDateTimeString(),
            'ModifiedBy' => $this->ModifiedBy,
            'ModifiedAt' => $this->ModifiedAt?->toDateTimeString(),
            'DeleteFlag' => (bool) $this->DeleteFlag,
        ];
    }
}