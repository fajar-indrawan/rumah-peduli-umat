<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Transaction\StoreTransactionRequest;
use App\Http\Requests\Transaction\UpdateTransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Models\Transaction;
use App\Services\TransactionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class TransactionController extends BaseApiController
{
    // Inject service melalui constructor
    public function __construct(
        protected TransactionService $transactionService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = (int) $request->input('per_page', 10);
            
            // Ambil data paginator dari Service
            $categories = $this->transactionService->getPaginatedCategories($perPage);
                    
            // Panggil paginatedResponse dari BaseApiController
            return $this->paginatedResponse(
                $categories, 
                'Data transaksi berhasil dimuat'
            );
        } catch (Throwable $e) {
            // menampilkan error apapun dari response JSON API.
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionRequest $request): JsonResponse
    {
        try {
            $transaction = $this->transactionService->storeTransaction($request->validated());

            return $this->successResponse(
                new TransactionResource($transaction), 
                'Transaksi berhasil ditambahkan', 
                201
            );
        } catch (Throwable $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        try {
            $transaction = $this->transactionService->getTransactionById($id);
            if (!$transaction) {
                return $this->errorResponse('Transaksi tidak ditemukan', 404);
            }
            return $this->successResponse(new TransactionResource($transaction), 'Success');
        } catch (Throwable $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransactionRequest $request, Transaction $transaction): JsonResponse
    {
        try {
            $transaction = $this->transactionService->updateTransaction($transaction, $request->validated());
            return $this->successResponse(new TransactionResource($transaction), 'Transaksi berhasil diperbarui');
        } catch (Throwable $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        try {
            $transaction = $this->transactionService->deleteTransaction($transaction);
            return $this->successResponse(null, 'Transaksi berhasil dihapus');
        } catch (Throwable $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
