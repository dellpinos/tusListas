<?php

namespace App\Models;

use App\Models\User;
use App\Models\Aumento;
use App\Models\Producto;
use App\Models\Provider;
use App\Models\Categoria;
use App\Models\Pendiente;
use App\Models\Fabricante;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Empresa extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'plan'
    ];

    public function productos()
    {
        return $this->hasMany(Producto::class);
    }
    public function aumentos()
    {
        return $this->hasMany(Aumento::class);
    }
    public function categorias()
    {
        return $this->hasMany(Categoria::class);
    }
    public function fabricantes()
    {
        return $this->hasMany(Fabricante::class);
    }
    public function pendientes()
    {
        return $this->hasMany(Pendiente::class);
    }
    public function providers()
    {
        return $this->hasMany(Provider::class);
    }
    public function users()
    {
        return $this->hasMany(User::class);
    }
}