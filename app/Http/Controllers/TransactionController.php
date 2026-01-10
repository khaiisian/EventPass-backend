<?php

namespace App\Http\Controllers;

use App\Http\Requests\Transaction\CreateTransactionRequest;
use App\Http\Requests\TransactionCreateRequest;
use App\Http\Requests\TransactionUpdateRequest;
use App\Http\Resources\TransactionResource;
use App\Services\TransactionService;
use Exception;
use App\Traits\HttpResponses;

class TransactionController extends Controller
{
    use HttpResponses;

    protected $_transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->_transactionService = $transactionService;
    }

    public function index()
    {
        try {
            $list = TransactionResource::collection($this->_transactionService->getAll());
            return $this->success('success', $list, 'Transactions retrieved successfully', 200);
        } catch (Exception $e) {
            return $this->fail('fail', null, $e->getMessage(), 500);
        }
    }

    public function store(TransactionCreateRequest $request)
    {
        try {
            $data = $request->validated();
            $data['CreatedBy'] = auth()->user()?->UserCode ?? 'admin';
            $data['CreatedAt'] = now();

            $result = TransactionResource::make($this->_transactionService->create($data));
            return $this->success('success', $result, 'Transaction created successfully.', 200);
        } catch (Exception $e) {
            return $this->fail('error', null, 'Transaction creation failed', 500);
        }
    }

    public function show($id)
    {
        try {
            $transaction = TransactionResource::make($this->_transactionService->getById($id));
            return $this->success('success', $transaction, 'Transaction retrieved successfully', 200);
        } catch (Exception $e) {
            return $this->fail('fail', null, $e->getMessage(), 500);
        }
    }

    public function update(TransactionUpdateRequest $request, $id)
    {
        try {
            $validatedData = $request->validated();
            $validatedData['ModifiedAt'] = now();
            $validatedData['ModifiedBy'] = 'admin';

            $update = $this->_transactionService->update($validatedData, $id);
            $resTransaction = TransactionResource::make($this->_transactionService->getById($id));

            if ($update) {
                return $this->success(true, $resTransaction, 'Transaction updated successfully', 200);
            } else {
                return $this->fail(false, null, 'Update failed', 500);
            }
        } catch (Exception $e) {
            return $this->fail(false, null, $e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $deleted = $this->_transactionService->destroy($id);
            if ($deleted) {
                return $this->success(true, null, 'Transaction deleted successfully', 200);
            } else {
                return $this->fail(false, null, 'Delete failed', 500);
            }
        } catch (Exception $e) {
            return $this->fail(false, null, $e->getMessage(), 500);
        }
    }

    public function buyTickets(CreateTransactionRequest $request)
    {
        try {
            $data = $request->validated();

            $transaction = TransactionResource::make(
                $this->_transactionService->buyTickets($data)
            );

            return $this->success('success', $transaction, 'Tickets purchased successfully.', 200);

        } catch (\Throwable $e) {
            \Log::error('Ticket purchase error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return $this->fail(
                'error',
                $e->getMessage() ?: 'Unknown error',
                'Ticket purchase failed',
                500
            );
        }
    }

}