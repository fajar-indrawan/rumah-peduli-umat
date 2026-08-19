<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class CategoryController extends BaseApiController
{
    // Inject service melalui constructor
    public function __construct(
        protected CategoryService $categoryService
    ) {}
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = (int) $request->input('per_page', 10);
            
            // Ambil data paginator dari Service
            $categories = $this->categoryService->getPaginatedCategories($perPage);
                    
            // Panggil paginatedResponse dari BaseApiController
            return $this->paginatedResponse(
                $categories, 
                'Data kategori berhasil dimuat'
            );
        } catch (Throwable $e) {
            // menampilkan error apapun dari response JSON API.
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        try {
            $category = $this->categoryService->storeCategory($request->validated());

            return $this->successResponse(
                new CategoryResource($category), 
                'Kategori berhasil ditambahkan', 
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
            $category = $this->categoryService->getCategoryById($id);
            if (!$category) {
                return $this->errorResponse('Kategori tidak ditemukan', 404);
            }
            return $this->successResponse(new CategoryResource($category), 'Success');
        } catch (Throwable $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category): JsonResponse
    {
        try {
            $category = $this->categoryService->updateCategory($category, $request->validated());
            return $this->successResponse(new CategoryResource($category), 'Kategori berhasil diperbarui');
        } catch (Throwable $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        try {
            $category = $this->categoryService->deleteCategory($category);
            return $this->successResponse(null, 'Kategori berhasil dihapus');
        } catch (Throwable $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
