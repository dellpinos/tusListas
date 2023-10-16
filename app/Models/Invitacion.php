<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitacion extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'token',
        'empresa_id'
    ];

    public function empresas()
    {
        return $this->belongsTo(Empresa::class);
    }
}
