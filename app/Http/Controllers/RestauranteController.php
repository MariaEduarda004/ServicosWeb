<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restaurante;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class RestauranteController extends Controller
{
    /**
     * @OA\Get(
     *     path="/restaurantes",
     *     summary="List all restaurants",
     *     tags={"Restaurant"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="nome", type="string"),
     *                 @OA\Property(property="endereco", type="string"),
     *                 @OA\Property(property="cidade", type="string"),
     *                 @OA\Property(property="cep", type="string"),
     *                 @OA\Property(property="telefone", type="string"),
     *                 @OA\Property(property="email", type="string"),
     *                 @OA\Property(property="tipo_cozinha", type="string"),
     *                 @OA\Property(property="descricao", type="string", nullable=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function index()
    {
        try {
            $restaurantes = Restaurante::all();
            return response()->json($restaurantes);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Internal Server Error',
                'message' => $e->getMessage(),
                'code' => 500
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/restaurantes",
     *     summary="Create a new restaurant",
     *     tags={"Restaurant"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nome", type="string"),
     *             @OA\Property(property="endereco", type="string"),
     *             @OA\Property(property="cidade", type="string"),
     *             @OA\Property(property="cep", type="string"),
     *             @OA\Property(property="telefone", type="string"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="tipo_cozinha", type="string"),
     *             @OA\Property(property="descricao", type="string", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successfully created restaurant",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="nome", type="string"),
     *             @OA\Property(property="endereco", type="string"),
     *             @OA\Property(property="cidade", type="string"),
     *             @OA\Property(property="cep", type="string"),
     *             @OA\Property(property="telefone", type="string"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="tipo_cozinha", type="string"),
     *             @OA\Property(property="descricao", type="string", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error creating restaurant"
     *     )
     * )
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nome' => 'required|string',
                'endereco' => 'required|string',
                'cidade' => 'required|string',
                'cep' => 'required|string',
                'telefone' => 'required|string',
                'email' => 'required|string|email',
                'tipo_cozinha' => 'required|string',
                'descricao' => 'nullable|string',
            ]);

            $restaurante = Restaurante::create($request->all());
            return response()->json($restaurante, 201);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Validation error',
                'message' => $e->errors(),
                'code' => 422
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error creating restaurant',
                'message' => $e->getMessage(),
                'code' => 400
            ], 400);
        }
    }

    /**
     * @OA\Get(
     *     path="/restaurantes/{id}",
     *     summary="Get a specific restaurant",
     *     tags={"Restaurant"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID restaurant",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="nome", type="string"),
     *             @OA\Property(property="endereco", type="string"),
     *             @OA\Property(property="cidade", type="string"),
     *             @OA\Property(property="cep", type="string"),
     *             @OA\Property(property="telefone", type="string"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="tipo_cozinha", type="string"),
     *             @OA\Property(property="descricao", type="string", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Restaurant not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function show(Restaurante $restaurante)
    {
        try {
            if (!$restaurante) {
                throw new ModelNotFoundException('Restaurant not found');
            }

            return response()->json($restaurante);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Not found',
                'message' => $e->getMessage(),
                'code' => 404
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Internal Server Error',
                'message' => $e->getMessage(),
                'code' => 500
            ], 500);
        }
    }
    /**
     * @OA\Put(
     *     path="/restaurantes/{id}",
     *     summary="Update an existing restaurant",
     *     tags={"Restaurant"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID restaurant",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nome", type="string"),
     *             @OA\Property(property="endereco", type="string"),
     *             @OA\Property(property="cidade", type="string"),
     *             @OA\Property(property="cep", type="string"),
     *             @OA\Property(property="telefone", type="string"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="tipo_cozinha", type="string"),
     *             @OA\Property(property="descricao", type="string", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Restaurant successfully updated",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer"),
     *             @OA\Property(property="nome", type="string"),
     *             @OA\Property(property="endereco", type="string"),
     *             @OA\Property(property="cidade", type="string"),
     *             @OA\Property(property="cep", type="string"),
     *             @OA\Property(property="telefone", type="string"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="tipo_cozinha", type="string"),
     *             @OA\Property(property="descricao", type="string", nullable=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Restaurant not found"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error updating restaurant"
     *     )
     * )
     */
    public function update(Request $request, Restaurante $restaurante)
    {
        try {
            $request->validate([
                'nome' => 'required|string',
                'endereco' => 'required|string',
                'cidade' => 'required|string',
                'cep' => 'required|string',
                'telefone' => 'required|string',
                'email' => 'required|string|email',
                'tipo_cozinha' => 'required|string',
                'descricao' => 'nullable|string',
            ]);

            if (!$restaurante) {
                throw new ModelNotFoundException('Restaurant not found');
            }

            $restaurante->update($request->all());
            return response()->json($restaurante);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Validation error',
                'message' => $e->errors(),
                'code' => 422
            ], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Not found',
                'message' => $e->getMessage(),
                'code' => 404
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error updating restaurant',
                'message' => $e->getMessage(),
                'code' => 400
            ], 400);
        }
    }
    /**
     * @OA\Delete(
     *     path="/restaurantes/{id}",
     *     summary="Delete a restaurant",
     *     tags={"Restaurant"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID restaurant",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Restaurant successfully deleted"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Restaurant not found"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function destroy(Restaurante $restaurante)
    {
        try {
            if (!$restaurante) {
                throw new ModelNotFoundException('Restaurante not found');
            }

            $restaurante->delete();
            return response()->json(null, 204);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Not found',
                'message' => $e->getMessage(),
                'code' => 404
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Internal Server Error',
                'message' => $e->getMessage(),
                'code' => 500
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/restaurantes/{id}/avaliacoes",
     *     summary="List all the reviews of a restaurant",
     *     tags={"Restaurant"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID restaurant",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="restaurante_id", type="integer"),
     *                 @OA\Property(property="user_id", type="integer"),
     *                 @OA\Property(property="avaliacao", type="number", format="float", minimum=1, maximum=5),
     *                 @OA\Property(property="comentario", type="string")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function avaliacoes(Restaurante $restaurante)
    {
        try {
            return response()->json($restaurante->avaliacoes);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Internal Server Error',
                'message' => $e->getMessage(),
                'code' => 500
            ], 500);
        }
    }
}