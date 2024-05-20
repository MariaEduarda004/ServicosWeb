<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Avaliacao;
use App\Models\Restaurante;

class AvaliacaoController extends Controller
{
    public function store(Request $request, Restaurante $restaurante)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'avaliacao' => 'required|numeric|min:1|max:5',
            'comentario' => 'nullable|string',
        ]);

        $avaliacao = new Avaliacao($request->all());
        $avaliacao->restaurante_id = $restaurante->id;
        $avaliacao->save();

        return response()->json($avaliacao, 201);
    }
}

