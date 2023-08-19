<?php

namespace App\Models;

use App\Models\Producto;
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
        'categoria_id'
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
    public function fabricante()
    {
        return $this->belongsTo(Fabricante::class);
    }
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
