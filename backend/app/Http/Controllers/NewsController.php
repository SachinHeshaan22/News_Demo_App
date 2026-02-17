<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $news = News::orderBy('date', 'desc')->orderBy('created_at', 'desc')->get();
            
            return response()->json([
                'success' => true,
                'data' => $news,
                'message' => 'News retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve news',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), News::getValidationRules(), News::getValidationMessages());
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Handle image upload
            $imageUrl = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = Str::random(40) . '.' . $image->getClientOriginalExtension();
                // Use Storage facade for more reliable file handling
                Storage::disk('public')->putFileAs('news_images', $image, $imageName);
                $imageUrl = 'storage/news_images/' . $imageName;
            }

            // Create news item
            $news = News::create([
                'date' => $request->date,
                'title' => $request->title,
                'category' => $request->category,
                'url' => $request->url,
                'image_url' => $imageUrl,
                'status' => $request->status ?? 'unpublished',
            ]);

            return response()->json([
                'success' => true,
                'data' => $news,
                'message' => 'News created successfully'
            ], 201);
        } catch (\Exception $e) {
            // Clean up uploaded image if creation fails
            if (isset($imageUrl)) {
                Storage::delete('public/news_images/' . basename($imageUrl));
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create news',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $news = News::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $news,
                'message' => 'News retrieved successfully'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'News not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve news',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $news = News::findOrFail($id);
            
            // Validate the request
            $validator = Validator::make($request->all(), News::getValidationRules($id), News::getValidationMessages());
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Handle image upload
            $imageUrl = $news->image_url;
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($news->image_url) {
                    Storage::delete('public/' . str_replace('storage/', '', $news->image_url));
                }
                
                $image = $request->file('image');
                $imageName = Str::random(40) . '.' . $image->getClientOriginalExtension();
                // Use Storage facade for more reliable file handling
                Storage::disk('public')->putFileAs('news_images', $image, $imageName);
                $imageUrl = 'storage/news_images/' . $imageName;
            }

            // Update news item
            $news->update([
                'date' => $request->date,
                'title' => $request->title,
                'category' => $request->category,
                'url' => $request->url,
                'image_url' => $imageUrl,
                'status' => $request->status ?? $news->status,
            ]);

            return response()->json([
                'success' => true,
                'data' => $news,
                'message' => 'News updated successfully'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'News not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update news',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $news = News::findOrFail($id);
            
            // Delete image if exists
            if ($news->image_url) {
                Storage::delete('public/' . str_replace('storage/', '', $news->image_url));
            }
            
            $news->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'News deleted successfully'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'News not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete news',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Publish a news item.
     */
    public function publish(string $id)
    {
        try {
            $news = News::findOrFail($id);
            $news->update(['status' => 'published']);
            
            return response()->json([
                'success' => true,
                'data' => $news,
                'message' => 'News published successfully'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'News not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to publish news',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Unpublish a news item.
     */
    public function unpublish(string $id)
    {
        try {
            $news = News::findOrFail($id);
            $news->update(['status' => 'unpublished']);
            
            return response()->json([
                'success' => true,
                'data' => $news,
                'message' => 'News unpublished successfully'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'News not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to unpublish news',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
