<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Info(title="API Documentation", version="1.0")
 */
class AuthController extends Controller
{
  /**
   * @OA\Post(
   *     path="/api/register",
   *     tags={"Authentication"},
   *     summary="Register a new user",
   *     @OA\RequestBody(
   *         required=true,
   *         @OA\JsonContent(
   *             required={"name","email","password"},
   *             @OA\Property(property="name", type="string", example="Maria Eduarda"),
   *             @OA\Property(property="email", type="string", format="email", example="maria.eduarda@example.com"),
   *             @OA\Property(property="password", type="string", format="password", example="password123")
   *         ),
   *     ),
   *     @OA\Response(
   *         response=201,
   *         description="User created successfully",
   *         @OA\JsonContent(
   *             @OA\Property(property="message", type="string", example="User created successfully")
   *         )
   *     ),
   *     @OA\Response(
   *         response=422,
   *         description="Validation error",
   *         @OA\JsonContent(
   *             @OA\Property(property="error", type="string", example="Validation error"),
   *             @OA\Property(property="message", type="object"),
   *             @OA\Property(property="code", type="integer", example=422)
   *         )
   *     ),
   *     @OA\Response(
   *         response=500,
   *         description="Server error",
   *         @OA\JsonContent(
   *             @OA\Property(property="error", type="string", example="Server error"),
   *             @OA\Property(property="message", type="string", example="An unexpected error occurred"),
   *             @OA\Property(property="code", type="integer", example=500)
   *         )
   *     )
   * )
   */
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

  /**
   * @OA\Post(
   *     path="/api/login",
   *     tags={"Authentication"},
   *     summary="Login a user",
   *     @OA\RequestBody(
   *         required=true,
   *         @OA\JsonContent(
   *             required={"email","password"},
   *             @OA\Property(property="email", type="string", format="email", example="maria.eduarda@example.com"),
   *             @OA\Property(property="password", type="string", format="password", example="password123")
   *         ),
   *     ),
   *     @OA\Response(
   *         response=200,
   *         description="User logged in successfully",
   *         @OA\JsonContent(
   *             @OA\Property(property="token", type="string", example="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9")
   *         )
   *     ),
   *     @OA\Response(
   *         response=401,
   *         description="Unauthorized",
   *         @OA\JsonContent(
   *             @OA\Property(property="message", type="string", example="Unauthorized")
   *         )
   *     ),
   *     @OA\Response(
   *         response=422,
   *         description="Validation error",
   *         @OA\JsonContent(
   *             @OA\Property(property="error", type="string", example="Validation error"),
   *             @OA\Property(property="message", type="object"),
   *             @OA\Property(property="code", type="integer", example=422)
   *         )
   *     ),
   *     @OA\Response(
   *         response=500,
   *         description="Server error",
   *         @OA\JsonContent(
   *             @OA\Property(property="error", type="string", example="Server error"),
   *             @OA\Property(property="message", type="string", example="An unexpected error occurred"),
   *             @OA\Property(property="code", type="integer", example=500)
   *         )
   *     )
   * )
   */
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

  /**
   * @OA\Get(
   *     path="/api/user",
   *     tags={"Authentication"},
   *     summary="Get authenticated user details",
   *     security={{"sanctum":{}}},
   *     @OA\Response(
   *         response=200,
   *         description="Authenticated user details",
   *         @OA\JsonContent(
   *             @OA\Property(property="id", type="integer", example=1),
   *             @OA\Property(property="name", type="string", example="Maria Eduarda"),
   *             @OA\Property(property="email", type="string", example="maria.eduarda@example.com"),
   *             @OA\Property(property="email_verified_at", type="string", example="null"),
   *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-05-21T00:00:00Z"),
   *             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-05-21T00:00:00Z")
   *         )
   *     ),
   *     @OA\Response(
   *         response=401,
   *         description="Unauthorized",
   *         @OA\JsonContent(
   *             @OA\Property(property="message", type="string", example="Unauthorized")
   *         )
   *     )
   * )
   */
  public function user(Request $request)
  {
    return response()->json($request->user());
  }
}
