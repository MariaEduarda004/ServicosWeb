<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Restaurante;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class RestauranteController extends Controller
{
    public function index()
    {
        try {
            $restaurantes = Restaurante::all();
            return response()->json($restaurantes);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro interno do servidor',
                'message' => $e->getMessage(),
                'code' => 500
            ], 500);
        }
    }

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
                'error' => 'Erro de validação',
                'message' => $e->errors(),
                'code' => 422
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao criar restaurante',
                'message' => $e->getMessage(),
                'code' => 400
            ], 400);
        }
    }

    public function show(Restaurante $restaurante)
    {
        try {
            if (!$restaurante) {
                throw new ModelNotFoundException('Restaurante não encontrado');
            }

            return response()->json($restaurante);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Não encontrado',
                'message' => $e->getMessage(),
                'code' => 404
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro interno do servidor',
                'message' => $e->getMessage(),
                'code' => 500
            ], 500);
        }
    }

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
                throw new ModelNotFoundException('Restaurante não encontrado');
            }

            $restaurante->update($request->all());
            return response()->json($restaurante);
        } catch (ValidationException $e) {
            return response()->json([
                'error' => 'Erro de validação',
                'message' => $e->errors(),
                'code' => 422
            ], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Não encontrado',
                'message' => $e->getMessage(),
                'code' => 404
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao atualizar restaurante',
                'message' => $e->getMessage(),
                'code' => 400
            ], 400);
        }
    }

    public function destroy(Restaurante $restaurante)
    {
        try {
            if (!$restaurante) {
                throw new ModelNotFoundException('Restaurante não encontrado');
            }

            $restaurante->delete();
            return response()->json(null, 204);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Não encontrado',
                'message' => $e->getMessage(),
                'code' => 404
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao excluir restaurante',
                'message' => $e->getMessage(),
                'code' => 500
            ], 500);
        }
    }

    public function avaliacoes(Restaurante $restaurante)
    {
        try {
            return response()->json($restaurante->avaliacoes);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao obter avaliações',
                'message' => $e->getMessage(),
                'code' => 500
            ], 500);
        }
    }
}
