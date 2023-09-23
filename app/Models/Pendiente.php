<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pendiente extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'precio',
        'desc_porc',
        'desc_duracion',
        'stock'
    ];
}