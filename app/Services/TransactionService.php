<?php

namespace App\Services;

use App\Models\TicketType;
use App\Models\Transaction;
use App\Models\TransactionTicket;
use App\Models\User;
use App\Traits\CodeGenerator;
use App\Models\Event;
use Illuminate\Support\Facades\DB;
use Str;

class TransactionService
{
    use CodeGenerator;

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
        $data['TransactionCode'] = $this->generateCode(
            'TRS',
            'TransactionId',
            'TransactionCode',
            Transaction::class
        );
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

    public function buyTickets(array $data)
    {
        return DB::transaction(function () use ($data) {

            $user = auth()->user();

            $transactionData = [
                'TransactionCode' => $this->generateCode(
                    'TRS',
                    'TransactionId',
                    'TransactionCode',
                    Transaction::class
                ),
                'UserId' => $user->UserId,
                'Email' => User::where('UserId', $user->UserId)->value('Email'),
                'Status' => false,
                'TotalAmount' => 0,
                'PaymentType' => $data['PaymentType'],
                'TransactionDate' => now(),
                'CreatedAt' => now(),
                'CreatedBy' => $user->UserCode ?? 'admin',
                'DeleteFlag' => false,
            ];

            $transaction = $this->connection()->create($transactionData);

            $totalAmount = 0;
            $totalQuantity = 0; // 👈 track total tickets in this transaction

            foreach ($data['Tickets'] as $ticket) {

                $ticketType = TicketType::where(
                    'TicketTypeCode',
                    $ticket['TicketTypeCode']
                )->first();

                if (!$ticketType) {
                    throw new \Exception(
                        "TicketTypeCode {$ticket['TicketTypeCode']} not found."
                    );
                }

                $ticketType->increment('SoldQuantity', $ticket['Quantity']);

                $totalQuantity += $ticket['Quantity'];

                for ($i = 0; $i < $ticket['Quantity']; $i++) {

                    $code = $this->generateCode(
                        'TTK',
                        'TransactionTicketId',
                        'TransactionTicketCode',
                        TransactionTicket::class
                    );

                    TransactionTicket::create([
                        'TransactionTicketCode' => $code,
                        'TicketTypeId' => $ticketType->TicketTypeId,
                        'TransactionId' => $transaction->TransactionId,
                        'QrImage' => null,
                        'Price' => $ticketType->Price,
                        'CreatedBy' => $user->UserCode ?? 'admin',
                        'CreatedAt' => now(),
                        'DeleteFlag' => false,
                    ]);

                    $totalAmount += $ticketType->Price;
                }
            }

            $transaction->update([
                'TotalAmount' => $totalAmount
            ]);

            Event::where('EventId', $data['EventId'])
                ->increment('SoldOutTicketQuantity', $totalQuantity);

            return $transaction;
        });
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