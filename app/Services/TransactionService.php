<?php

namespace App\Services;

use App\Models\TicketType;
use App\Models\Transaction;
use App\Models\TransactionTicket;
use App\Models\User;
use App\Traits\CodeGenerator;
use App\Models\Event;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class TransactionService
{
    use CodeGenerator;

    public function connection()
    {
        return new Transaction;
    }

    public function getAll($perPage = 10)
    {
        return $this->connection()
            ->where('DeleteFlag', false)
            ->orderByDesc('CreatedAt')
            ->paginate($perPage);
    }

    public function search(array $params)
    {
        $query = Transaction::query()
            ->where('DeleteFlag', false)
            ->with([
                'user',
                'transactionTickets.ticketType'
            ]);


        if (array_key_exists('status', $params) && $params['status'] !== 'all') {
            $status = filter_var($params['status'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            if (!is_null($status)) {
                $query->where('Status', $status);
            }
        }

        if (!empty($params['payment_type'])) {
            $query->where('PaymentType', $params['payment_type']);
        }

        // Search by transaction code or email
        if (!empty($params['search'])) {
            $query->where(function ($q) use ($params) {
                $q->where('TransactionCode', 'LIKE', '%' . $params['search'] . '%')
                    ->orWhere('Email', 'LIKE', '%' . $params['search'] . '%');
            });
        }

        // Sorting
        switch ($params['sort_by'] ?? null) {
            case 'date_asc':
                $query->orderBy('TransactionDate', 'asc');
                break;

            case 'date_desc':
                $query->orderBy('TransactionDate', 'desc');
                break;

            case 'amount_asc':
                $query->orderBy('TotalAmount', 'asc');
                break;

            case 'amount_desc':
                $query->orderBy('TotalAmount', 'desc');
                break;

            case 'created_asc':
                $query->orderBy('CreatedAt', 'asc');
                break;

            case 'created_desc':
                $query->orderBy('CreatedAt', 'desc');
                break;

            default:
                $query->orderBy('CreatedAt', 'desc');
        }

        return $query->paginate($params['per_page'] ?? 10);
    }

    public function getById($id)
    {
        return $this->connection()
            ->with([
                'user',
                'transactionTickets.ticketType.event',
            ])
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

            // Create the main transaction
            $transactionData = [
                'TransactionCode' => $this->generateCode(
                    'TRS',
                    'TransactionId',
                    'TransactionCode',
                    Transaction::class
                ),
                'UserId' => $user->UserId,
                'Email' => User::where('UserId', $user->UserId)->value('Email'),
                'Status' => true,
                'TotalAmount' => 0,
                'PaymentType' => $data['PaymentType'],
                'TransactionDate' => now(),
                'CreatedAt' => now(),
                'CreatedBy' => $user->UserCode ?? 'admin',
                'DeleteFlag' => false,
            ];

            $transaction = $this->connection()->create($transactionData);

            $totalAmount = 0;
            $totalQuantity = 0;

            // Ensure QR folder exists
            $qrFolder = storage_path('app/public/qr-tickets');
            if (!file_exists($qrFolder)) {
                mkdir($qrFolder, 0755, true);
            }

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

                    // Generate ULID-based ticket code
                    $code = 'T' . Str::ulid();

                    // Create QR code (PNG) from the ticket code
                    $qrImage = QrCode::format('png')
                        ->size(300)
                        ->generate($code);

                    $fileName = Str::uuid();
                    $filePath = 'qr-tickets/' . $fileName . '.png';

                    Storage::disk('public')->put($filePath, $qrImage);

                    TransactionTicket::create([
                        'TransactionTicketCode' => $code,
                        'TicketTypeId' => $ticketType->TicketTypeId,
                        'TransactionId' => $transaction->TransactionId,
                        'QrImage' => $filePath,
                        'Price' => $ticketType->Price,
                        'CreatedBy' => $user->UserCode ?? 'admin',
                        'CreatedAt' => now(),
                        'DeleteFlag' => false,
                    ]);

                    $totalAmount += $ticketType->Price;
                }
            }

            // Update total amount in transaction
            $transaction->update([
                'TotalAmount' => $totalAmount
            ]);

            // Update event sold quantity
            Event::where('EventId', $data['EventId'])
                ->increment('SoldOutTicketQuantity', $totalQuantity);

            return $transaction;
        });
    }


    public function ticketHistory()
    {
        $id = auth()->user()->UserId;
        return $this->connection()
            ->with([
                'user',
                'transactionTickets.ticketType',
            ])
            ->where('UserId', $id)
            ->where('DeleteFlag', false)
            ->get();

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