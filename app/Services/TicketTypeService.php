<?php

namespace App\Services;

use App\Models\TicketType;
use App\Traits\CodeGenerator;

class TicketTypeService
{
    use CodeGenerator;
    public function connection()
    {
        return new TicketType;
    }

    public function getAll($perPage = 10)
    {
        return $this->connection()
            ->where('DeleteFlag', false)
            ->paginate($perPage);
    }

    public function getById($id)
    {
        return $this->connection()
            ->where('TicketTypeId', $id)
            ->where('DeleteFlag', false)
            ->firstOrFail();
    }

    public function create(array $data)
    {
        $data['CreatedBy'] = auth()->user()?->UserCode ?? 'admin';
        $data['CreatedAt'] = now();
        $data['EventCode'] = $this->generateCode('TT', 'TicketTypeId', 'TicketTypeCode', TicketType::class);
        return $this->connection()->create($data);
    }

    public function update(array $data, $id)
    {
        $data['ModifiedAt'] = now();
        $data['ModifiedBy'] = auth()->user()?->UserCode ?? 'admin';
        $ticketType = $this->connection()
            ->where('DeleteFlag', false)
            ->findOrFail($id);

        $ticketType->update($data);
        return $ticketType;
    }

    public function destroy($id)
    {
        $ticketType = $this->connection()
            ->where('DeleteFlag', false)
            ->findOrFail($id);

        $ticketType->DeleteFlag = true;
        $ticketType->ModifiedAt = now();
        $ticketType->ModifiedBy = "system";

        return $ticketType->save();
    }

    public function generateTicketTypeCode()
    {
        $last = $this->connection()::orderBy('TicketTypeId', 'desc')->first();

        if (!$last) {
            return 'TT0001';
        }

        $lastCode = $last->TicketTypeCode;
        $number = (int) substr($lastCode, 2);
        $number++;
        return 'TT' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}