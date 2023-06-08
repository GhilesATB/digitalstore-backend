<?php

namespace App\Http\Api\Controllers;

use App\Exceptions\CategoryNameAlreadyExistsException;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResponse;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Symfony\Component\HttpFoundation\Response;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $categories = Category::paginate(request()->input('pageSize'), ['*'], 'page', request()->input('page') + 1);

        $categoryCollection = new CategoryCollection($categories);

        return $categoryCollection->withStatusCode(Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryRequest $request): JsonResponse
    {
        if (Category::hasName($request->input('name'))->exists()) {
            throw new CategoryNameAlreadyExistsException();
        }

        $data = $request->validated();

        if ($request->hasFile('image')) {

            $imgName = time().'.'.$request->image->extension();
            $thumbnailName = time().'_thumbnail.'.$request->image->extension();

            Storage::disk('public')->putFileAs('', $request->image, $imgName);

            $img = Image::make($request->image)
                ->resize(320, 240)
                ->encode($request->image->extension());

            Storage::disk('public')->put($thumbnailName, $img);

            $data = array_merge($data,
                ['image' => Storage::url($imgName), 'thumbnail' => Storage::url($thumbnailName)]);
        }

        $category = Category::create($data);

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
        $data = $request->validated();

        if (Category::hasName($request->input('name'))->exists() && $request->input('name') !== $category->name) {
            throw new CategoryNameAlreadyExistsException();
        }

        if ($request->hasFile('image')) {

            $filesToDelete = $category->pluck('image', 'thumbnail')->toArray();

            Storage::disk('public')->delete($filesToDelete);

            $imgName = time().'.'.$request->image->extension();
            $thumbnailName = time().'thumbnail_.'.$request->image->extension();

            Storage::disk('public')->putFileAs('', $request->image, $imgName);

            $img = Image::make($request->image)
                ->resize(320, 240)
                ->encode($request->image->extension());

            Storage::disk('public')->put('', $thumbnailName, $img);

            $data = array_merge($data,
                ['image' => Storage::url($imgName), 'thumbnail' => Storage::url($thumbnailName)]);
        }

        $category->update($data);

        return CategoryResponse::make($category)
            ->withStatusCode(Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category): JsonResponse
    {

        $filesToDelete = $category->pluck('image', 'thumbnail')->toArray();

        Storage::disk('public')->delete($filesToDelete);

        $category->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
