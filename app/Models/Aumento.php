<?php

namespace App\Models;

use App\Models\Empresa;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Aumento extends Model
{
    use HasFactory;

    protected $fillable = [
        'porcentaje',
        'tipo',
        'username',
        'nombre',
        'afectados',
        'empresa_id'
    ];

    public function empresas()
    {
        return $this->belongsTo(Empresa::class);
    }
}
