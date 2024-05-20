<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Restaurante extends Model
{
    protected $fillable = [
        'nome',
        'endereco',
        'cidade',
        'cep',
        'telefone',
        'email',
        'tipo_cozinha',
        'descricao'
    ];

    public function avaliacoes()
    {
        return $this->hasMany(Avaliacao::class);
    }
}
