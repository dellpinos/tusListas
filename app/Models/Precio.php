<?php

namespace App\Models;

use App\Models\Producto;
use App\Models\Provider;
use App\Models\Categoria;
use App\Models\Fabricante;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Precio extends Model
{
    use HasFactory;

    protected $fillable = [
        'precio',
        'dolar',
        'fabricante_id',
        'categoria_id',
        'provider_id',
        'contador_update',
        'desc_porc',
        'desc_duracion',
        'desc_acu',
        'empresa_id'
    ];

    public function categorias()
    {
        return $this->belongsTo(Categoria::class);
    }
    public function fabricantes()
    {
        return $this->belongsTo(Fabricante::class);
    }
    public function providers()
    {
        return $this->belongsTo(Provider::class);
    }
    // Debo revisar todas estas relaciones, esta tuve que corregirla (estaba invertida)
    public function producto()
    {
        return $this->hasMany(Producto::class);
    }
    // public function productos()
    // {
    //     return $this->belongsTo(Producto::class);
    // }
}
