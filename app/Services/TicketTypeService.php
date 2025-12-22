<?php

namespace App\Services;

use App\Models\TicketType;

class TicketTypeService
{
    public function connection()
    {
        return new TicketType;
    }

    public function getAll()
    {
        return $this->connection()
            ->where('DeleteFlag', false)
            ->get();
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
        $data['TicketTypeCode'] = $this->generateTicketTypeCode();
        return $this->connection()->create($data);
    }

    public function update(array $data, $id)
    {
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