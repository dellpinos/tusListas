<?php

namespace App\Models;

use App\Models\Precio;
use App\Models\Empresa;
use App\Models\Producto;
use App\Models\Provider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Categoria extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'ganancia',
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
    public function providers()
    {
        return $this->belongsTo(Provider::class);
    }
    public function empresas()
    {
        return $this->belongsTo(Empresa::class);
    }
}
