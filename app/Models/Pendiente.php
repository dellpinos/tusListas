<?php

namespace App\Models;

use App\Models\Empresa;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pendiente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'precio',
        'desc_porc',
        'desc_duracion',
        'stock',
        'empresa_id'
    ];

    public function empresas()
    {
        return $this->belongsTo(Empresa::class);
    }
}