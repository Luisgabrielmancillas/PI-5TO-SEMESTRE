<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistroActuador extends Model
{
    protected $table = 'registro_actuador';
    protected $primaryKey = 'id_registro';

    protected $fillable = [
        'id_actuador',
        'estado_anterior',
        'estado_actual',
    ];

    public $timestamps = true;
}
