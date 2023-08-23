<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContentController extends Controller
{
    /**
     * Create Post
     * @param Request $request
     */
    public function createPost(Request $request)
    {
        try {
            // validation
            $validatePost = Validator::make($request->all(), [
                'slug' => 'required|unique:posts,slug',
                'content' => 'required',
            ]);

            // if incoming informations are wrong
            if ($validatePost->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => "Error during post validation",
                    'error' => $validatePost->errors()
                ], 401);
            }

            $post = Post::create([
                'slug' => $request->slug,
                'content' => $request->content
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Post created successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }
}
