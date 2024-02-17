<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    protected $fillable = [
        'monto_dolar',
        'empresa_id',
        'producto_id'
    ];

    public function empresas()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function productos()
    {
        return $this->belongsTo(Producto::class);
    }
}
