<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    use HasFactory;

    protected $fillable = [
        'monto_dolar',
        'empresa_id',
        'monto'
    ];

    public function empresas()
    {
        return $this->belongsTo(Empresa::class);
    }
}
