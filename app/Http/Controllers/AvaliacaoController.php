<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Avaliacao;
use App\Models\Restaurante;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class AvaliacaoController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/restaurantes/{restaurante}/avaliacoes",
     *     tags={"Review"},
     *     summary="Create a new review for a specific restaurant",
     *     @OA\Parameter(
     *         name="restaurant",
     *         in="path",
     *         required=true,
     *         description="ID of the restaurant",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id","avaliacao"},
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="avaliacao", type="number", format="float", example=4.5),
     *             @OA\Property(property="comentario", type="string", example="Great place!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Review created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="user_id", type="integer", example=1),
     *             @OA\Property(property="restaurante_id", type="integer", example=1),
     *             @OA\Property(property="avaliacao", type="number", format="float", example=4.5),
     *             @OA\Property(property="comentario", type="string", example="Great place!"),
     *             @OA\Property(property="created_at", type="string", format="date-time", example="2024-05-21T00:00:00Z"),
     *             @OA\Property(property="updated_at", type="string", format="date-time", example="2024-05-21T00:00:00Z")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Bad Request"),
     *             @OA\Property(property="message", type="string", example="Invalid request data"),
     *             @OA\Property(property="code", type="integer", example=400)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Not Found"),
     *             @OA\Property(property="message", type="string", example="Restaurant not found"),
     *             @OA\Property(property="code", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Validation error"),
     *             @OA\Property(property="message", type="object", example={"user_id": {"The user id field is required."}}),
     *             @OA\Property(property="code", type="integer", example=422)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Server error"),
     *             @OA\Property(property="message", type="string", example="Internal Server Error"),
     *             @OA\Property(property="code", type="integer", example=500)
     *         )
     *     )
     * )
     */
    public function store(Request $request, Restaurante $restaurante)
    {
        try {
            $request->validate([
                'user_id' => 'required|integer',
                'avaliacao' => 'required|numeric|min:1|max:5',
                'comentario' => 'nullable|string',
            ]);

            $avaliacao = new Avaliacao($request->all());
            $avaliacao->restaurante_id = $restaurante->id;
            $avaliacao->save();

            return response()->json($avaliacao, 201);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Validation error',
                'message' => $e->errors(),
                'code' => 422
            ], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Not Found',
                'message' => 'Restaurant not found',
                'code' => 404
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Bad Request',
                'message' => 'Invalid request data',
                'code' => 400
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Server error',
                'message' => 'Internal Server Error',
                'code' => 500
            ], 500);
        }
    }
}
