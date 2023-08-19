<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fabricante extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'telefono',
        'vendedor',
        'descripcion'

    ];

    public function productos()
    {
        return $this->hasMany((Producto::class));
    }
    public function precios()
    {
        return $this->hasMany((Precio::class));
    }
}
