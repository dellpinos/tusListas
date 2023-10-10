<?php

namespace App\Models;

use App\Models\Precio;
use App\Models\Empresa;
use App\Models\Provider;
use App\Models\Categoria;
use App\Models\Fabricante;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'codigo',
        'fabricante_id',
        'provider_id',
        'categoria_id',
        'ganancia_tipo',
        'ganancia_prod',
        'precio_id',
        'contador_show',
        'unidad_fraccion',
        'contenido_total',
        'ganancia_fraccion',
        'stock',
        'empresa_id'
    ];

    public function fabricantes()
    {
        return $this->belongsTo(Fabricante::class);
    }
    public function categorias()
    {
        return $this->belongsTo(Categoria::class);
    }
    public function providers()
    {
        return $this->belongsTo(Provider::class);
    }
    public function precioss()
    {
        return $this->hasMany(Precio::class);
    }
    public function empresas()
    {
        return $this->belongsTo(Empresa::class);
    }
}
