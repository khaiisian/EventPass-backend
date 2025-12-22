<?php

namespace App\Services;

use App\Models\Transaction;

class TransactionService
{
    public function connection()
    {
        return new Transaction;
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
            ->where('TransactionId', $id)
            ->where('DeleteFlag', false)
            ->firstOrFail();
    }

    public function create(array $data)
    {
        $data['TransactionCode'] = $this->generateTransactionCode();
        return $this->connection()->create($data);
    }

    public function update(array $data, $id)
    {
        $transaction = $this->connection()
            ->where('DeleteFlag', false)
            ->findOrFail($id);

        $transaction->update($data);
        return $transaction;
    }

    public function destroy($id)
    {
        $transaction = $this->connection()
            ->where('DeleteFlag', false)
            ->findOrFail($id);

        $transaction->DeleteFlag = true;
        $transaction->ModifiedAt = now();
        $transaction->ModifiedBy = "system";

        return $transaction->save();
    }

    public function generateTransactionCode()
    {
        $last = $this->connection()::orderBy('TransactionId', 'desc')->first();

        if (!$last) {
            return 'TRX0001';
        }

        $lastCode = $last->TransactionCode;
        $number = (int) substr($lastCode, 3);
        $number++;
        return 'TRX' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}