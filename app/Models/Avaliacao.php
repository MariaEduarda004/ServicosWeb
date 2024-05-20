<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Avaliacao extends Model
{
    protected $fillable = [
        'restaurante_id',
        'user_id',
        'avaliacao',
        'comentario'
    ];

    public function restaurante()
    {
        return $this->belongsTo(Restaurante::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
