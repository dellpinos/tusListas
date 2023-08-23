<?php

namespace App\Models;

use App\Models\Precio;
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
        'ganancia_prod'
    ];


    public function fabricante()
    {
        return $this->belongsTo(Fabricante::class);
    }
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }
    
}
