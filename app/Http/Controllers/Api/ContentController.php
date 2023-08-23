<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Beat;
use App\Models\Like;
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

    /**
     * Create Beat
     * @param Request $request
     */
    public function createBeat(Request $request)
    {
        try {
            // validation
            $validateBeat = Validator::make($request->all(), [
                'slug' => 'required|unique:beats,slug',
                'title' => 'required',
                'premium_file' => 'mimes:mp4,txt,png,xls,pdf|max:2048',
                'free_file' => 'required|mimes:mp4,txt,png,xls,pdf|max:2048',
            ]);

            // if incoming informations are wrong
            if ($validateBeat->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => "Error during beat validation",
                    'error' => $validateBeat->errors()
                ], 401);
            }


            if (!$request->file()) {
                return response()->json([
                    'success' => false,
                    'message' => "There is no free file in your request",
                    'error' => $validateBeat->errors()
                ], 401);
            }

            $beat = new Beat();
            if ($request->file('premium_file')) {
                $name = time() . '_premium_file.' . $request->file('premium_file')->getClientOriginalExtension();
                $beat->premium_file = $request->file('premium_file')->storeAs('premium_file', $name, 'private');
            }
            $name = time() . '_free_file.' . $request->file('free_file')->getClientOriginalExtension();
            $beat->free_file = $request->file('free_file')->storeAs('free_file', $name, 'public');
            $beat->title = $request->title;
            $beat->slug = $request->slug;
            $beat->save();

            return response()->json([
                'success' => true,
                'message' => 'Beat created successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Add Beat's like
     * @param Request $request
     */
    public function addPostLike(Request $request)
    {
        try {
            // validation
            $validateBeat = Validator::make($request->all(), [
                'user_id' => 'required',
                'post_id' => 'required',
            ]);

            // if incoming informations are wrong
            if ($validateBeat->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => "Error during beat's like validation",
                    'error' => $validateBeat->errors()
                ], 401);
            }

            $like = new Like(['user_id' => $request->user_id]);
            $post = Post::find($request->post_id);
            $post->likes()->save($like);

            return response()->json([
                'success' => true,
                'message' => 'Beat created successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }
}
