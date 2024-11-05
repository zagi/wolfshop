<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Item;

class ItemController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $items = Item::all();
            return response()->json($items);
        } catch (\Exception $e) {
            Log::error("Error fetching items: {$e->getMessage()}");
            return response()->json(['error' => 'Failed to retrieve items'], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sellIn' => 'required|integer',
            'quality' => 'required|integer|min:0|max:50',
        ]);

        try {
            $item = Item::create($validated);
            return response()->json($item, 201);
        } catch (\Exception $e) {
            Log::error("Error creating item: {$e->getMessage()}");
            return response()->json(['error' => 'Failed to create item'], 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $item = Item::findOrFail($id);
            return response()->json($item);
        } catch (\Exception $e) {
            Log::error("Error fetching item with ID {$id}: {$e->getMessage()}");
            return response()->json(['error' => 'Item not found'], 404);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'sellIn' => 'sometimes|integer',
            'quality' => 'sometimes|integer|min:0|max:50',
        ]);

        try {
            $item = Item::findOrFail($id);
            $item->update($validated);
            return response()->json($item);
        } catch (\Exception $e) {
            Log::error("Error updating item with ID {$id}: {$e->getMessage()}");
            return response()->json(['error' => 'Failed to update item'], 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $item = Item::findOrFail($id);
            $item->delete();
            return response()->json(['message' => 'Item deleted successfully']);
        } catch (\Exception $e) {
            Log::error("Error deleting item with ID {$id}: {$e->getMessage()}");
            return response()->json(['error' => 'Failed to delete item'], 500);
        }
    }

    public function uploadImage(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        try {
            $item = Item::findOrFail($id);

            $uploadedFileUrl = Storage::disk('cloudinary')->putFile('images', $request->file('image'));

            $item->setImgUrl($uploadedFileUrl);
            $item->save();

            return response()->json([
                'message' => 'Image uploaded successfully',
                'imgUrl' => $uploadedFileUrl,
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error uploading image for item with ID {$id}: {$e->getMessage()}");
            return response()->json(['error' => 'Failed to upload image'], 500);
        }
    }
}
