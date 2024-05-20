<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
  public function register(Request $request)
  {
    try {
      $request->validate([
        'name' => 'required|string',
        'email' => 'required|string|email|unique:users',
        'password' => 'required|string|min:8',
      ]);

      $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
      ]);

      return response()->json(['message' => 'User created successfully'], 201);
    } catch (ValidationException $e) {
      return response()->json([
        'error' => 'Validation error',
        'message' => $e->errors(),
        'code' => 422
      ], 422);
    } catch (\Exception $e) {
      return response()->json([
        'error' => 'Server error',
        'message' => $e->getMessage(),
        'code' => 500
      ], 500);
    }
  }

  public function login(Request $request)
  {
    try {
      $request->validate([
        'email' => 'required|string|email',
        'password' => 'required|string',
      ]);

      $user = User::where('email', $request->email)->first();

      if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Unauthorized'], 401);
      }

      $token = $user->createToken('authToken')->plainTextToken;

      return response()->json(['token' => $token], 200);
    } catch (ValidationException $e) {
      return response()->json([
        'error' => 'Validation error',
        'message' => $e->errors(),
        'code' => 422
      ], 422);
    } catch (\Exception $e) {
      return response()->json([
        'error' => 'Server error',
        'message' => $e->getMessage(),
        'code' => 500
      ], 500);
    }
  }

  public function user(Request $request)
  {
    return response()->json($request->user());
  }
}
