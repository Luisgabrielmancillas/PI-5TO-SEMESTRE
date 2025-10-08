<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ConfigSensore
 * 
 * @property int $id_config
 * @property int $id_hortaliza
 * @property int $id_sensor
 * @property float $valor_min_acept
 * @property float $valor_max_acept
 * 
 * @property SeleccionHortalizas $seleccion_hortaliza
 * @property Sensores $sensore
 *
 * @package App\Models
 */
class ConfigSensores extends Model
{
	protected $table = 'config_sensores';
	protected $primaryKey = 'id_config';
	public $timestamps = false;

	protected $casts = [
		'id_hortaliza' => 'int',
		'id_sensor' => 'int',
		'valor_min_acept' => 'float',
		'valor_max_acept' => 'float'
	];

	protected $fillable = [
		'id_hortaliza',
		'id_sensor',
		'valor_min_acept',
		'valor_max_acept'
	];

	public function seleccion_hortaliza()
	{
		return $this->belongsTo(SeleccionHortalizas::class, 'id_hortaliza');
	}

	public function sensore()
	{
		return $this->belongsTo(Sensores::class, 'id_sensor');
	}
}
