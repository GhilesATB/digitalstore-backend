<?php

namespace App\Http\Api\Controllers;

use App\Exceptions\CategoryNameAlreadyExistsApiException;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResponse;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $categories = Category::all();

        $categoryCollection = new CategoryCollection($categories);

        return $categoryCollection->withStatusCode(Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request): JsonResponse
    {
        $category = Category::make($request->validated());

        if (Category::hasName($request->input('name'))->exists()) {
            throw new CategoryNameAlreadyExistsApiException();
        }

        $category->save();

        return CategoryResponse::make($category)
            ->withStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category): JsonResponse
    {
        return CategoryResponse::make($category)
            ->withStatusCode(Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Category $category, CategoryRequest $request): JsonResponse
    {
        $categoryUpdate = Category::make($request->validated());

        if (Category::hasName($categoryUpdate->name)->exists() && $categoryUpdate !== $category->name) {
            throw new CategoryNameAlreadyExistsApiException();
        }

        $category->update($categoryUpdate->toArray());

        return CategoryResponse::make($category)
            ->withStatusCode(Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category): JsonResponse
    {
        $category->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
