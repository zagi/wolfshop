<?php

namespace App\Http\Controllers;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Item;

class ItemController extends Controller
{
    public function index(): JsonResponse
    {
        $items = Item::all();
        return response()->json($items);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate(
            [
            'name' => 'required|string|max:255',
            'sellIn' => 'required|integer',
            'quality' => 'required|integer|min:0|max:50',
            ]
        );

        $item = Item::create($validated);
        return response()->json($item, 201);
    }

    public function show(int $id): JsonResponse
    {
        $item = Item::findOrFail($id);
        return response()->json($item);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $item = Item::findOrFail($id);

        $validated = $request->validate(
            [
            'name' => 'sometimes|string|max:255',
            'sellIn' => 'sometimes|integer',
            'quality' => 'sometimes|integer|min:0|max:50',
            ]
        );

        $item->update($validated);
        return response()->json($item);
    }

    public function destroy(int $id): JsonResponse
    {
        $item = Item::findOrFail($id);
        $item->delete();

        return response()->json(['message' => 'Item deleted successfully']);
    }

    public function uploadImage(Request $request, int $id): JsonResponse
    {
        $request->validate(
            [
            'image' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
            ]
        );

        $item = Item::findOrFail($id);

        $uploadedFileUrl = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();

        $item->setImgUrl($uploadedFileUrl);
        $item->save();

        return response()->json(
            [
            'message' => 'Image uploaded successfully',
            'imgUrl' => $uploadedFileUrl,
            ],
            200
        );
    }
}
