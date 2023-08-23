<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * create user
     * @param Request $request
     */
    public function createUser(Request $request)
    {
        try {
            // validation
            $validateUser = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required'
            ]);

            // if incoming informations are wrong
            if ($validateUser->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => "Error during validation",
                    'error' => $validateUser->errors()
                ], 401);
            }

            // adding user in database
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // 
            return response()->json([
                'success' => true,
                'message' => 'User created successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken,
                'data' => Auth::user(),
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function loginUser(Request $request)
    {
        try {
            // validation
            $validateUser = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            // if incoming informations are wrong
            if ($validateUser->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => "Error during validation",
                    'error' => $validateUser->errors()
                ], 401);
            }

            if (!Auth::attempt($request->only(['email', 'password']))) {
                return response()->json([
                    'success' => false,
                    'message' => "Email and Password do not match with our record.",
                ], 401);
            }

            $user = User::where('email', $request->email)->first();

            return response()->json([
                'success' => true,
                'message' => 'User logged in successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken,
                'datas' => Auth::user(),
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function logOut(Request $request)
    {
        try {
            // validation
            $validateUser = Validator::make($request->all(), [
                'user_id' => 'required',
            ]);

            // if incoming informations are wrong
            if ($validateUser->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => "Error during validation",
                    'error' => $validateUser->errors()
                ], 401);
            }

            $user = User::find($request->user_id);
            $user->tokens()->delete();

            return response()->json([
                'success' => true,
                'message' => 'User logged out successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ]);
        }
    }
}
