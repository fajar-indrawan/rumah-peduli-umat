<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\ChartOfAccount\StoreChartOfAccountRequest;
use App\Http\Requests\ChartOfAccount\UpdateChartOfAccountRequest;
use App\Http\Resources\ChartOfAccountResource;
use App\Models\ChartOfAccount;
use App\Services\ChartOfAccountService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class ChartOfAccountController extends BaseApiController
{
    // Inject service melalui constructor
    public function __construct(
        protected ChartOfAccountService $chartOfAccountService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = (int) $request->input('per_page', 10);
            
            // Ambil data paginator dari Service
            $categories = $this->chartOfAccountService->getPaginatedCategories($perPage);
                    
            // Panggil paginatedResponse dari BaseApiController
            return $this->paginatedResponse(
                $categories, 
                'Data chart of account berhasil dimuat'
            );
        } catch (Throwable $e) {
            // menampilkan error apapun dari response JSON API.
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreChartOfAccountRequest $request): JsonResponse
    {
        try {
            $chartOfAccount = $this->chartOfAccountService->storeChartOfAccount($request->validated());

            return $this->successResponse(
                new ChartOfAccountResource($chartOfAccount), 
                'Chart Of Account berhasil ditambahkan', 
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
            $category = $this->chartOfAccountService->getChartOfAccountById($id);
            if (!$category) {
                return $this->errorResponse('Kategori tidak ditemukan', 404);
            }
            return $this->successResponse(new ChartOfAccountResource($category), 'Success');
        } catch (Throwable $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateChartOfAccountRequest $request, ChartOfAccount $chartOfAccount): JsonResponse
    {
        try {
            $chartOfAccount = $this->chartOfAccountService->updateCategory($chartOfAccount, $request->validated());
            return $this->successResponse(new ChartOfAccountResource($chartOfAccount), 'Chart Of Account berhasil diperbarui');
        } catch (Throwable $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChartOfAccount $chartOfAccount)
    {
        try {
            $chartOfAccount = $this->chartOfAccountService->deleteCategory($chartOfAccount);
            return $this->successResponse(null, 'Chart Of Account berhasil dihapus');
        } catch (Throwable $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
