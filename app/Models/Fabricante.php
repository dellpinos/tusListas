<?php

namespace App\Models;

use App\Models\Precio;
use App\Models\Empresa;
use App\Models\Producto;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Fabricante extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'telefono',
        'vendedor',
        'descripcion',
        'empresa_id'

    ];

    public function productos()
    {
        return $this->hasMany(Producto::class);
    }
    public function precios()
    {
        return $this->hasMany(Precio::class);
    }
    public function empresas()
    {
        return $this->belongsTo(Empresa::class);
    }
}
