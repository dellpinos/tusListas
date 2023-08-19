<?php

namespace App\Models;

use App\Models\Precio;
use App\Models\Producto;
use App\Models\ProvidersCategorias;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Categoria extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'ganancia',
        'providersCategorias_id'
    ];

    public function productos()
    {
        return $this->hasMany((Producto::class));
    }
    public function providersCategrorias()
    {
        return $this->hasMany((ProvidersCategorias::class));
    }
    public function precios()
    {
        return $this->hasMany((Precio::class));
    }
}
