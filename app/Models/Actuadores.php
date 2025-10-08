<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Actuadore
 * 
 * @property int $id_actuador
 * @property string $nombre
 * @property string $tipo
 * @property int|null $bus
 * @property string|null $address
 * @property string $modo_activacion
 * @property bool $estado_inicial
 * @property bool $estado_actual
 *
 * @package App\Models
 */
class Actuadores extends Model
{
	protected $table = 'actuadores';
	protected $primaryKey = 'id_actuador';
	public $timestamps = false;

	protected $casts = [
		'bus' => 'int',
		'estado_inicial' => 'bool',
		'estado_actual' => 'bool'
	];

	protected $fillable = [
		'nombre',
		'tipo',
		'bus',
		'address',
		'modo_activacion',
		'estado_inicial',
		'estado_actual'
	];
}
