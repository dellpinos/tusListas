<?php

namespace App\Models;

use App\Models\Producto;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Provider extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'email',
        'telefono',
        'vendedor',
        'web',
        'ganancia'
    ];

    public function productos()
    {
        return $this->hasMany(Producto::class);
    }
}
