<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Sensore
 * 
 * @property int $id_sensor
 * @property string $nombre
 * @property string $bus
 * @property string $address
 * @property string $tasa_flujo
 * @property string $modo_salida
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|ConfigSensores[] $config_sensores
 * @property Collection|Mediciones[] $mediciones
 *
 * @package App\Models
 */
class Sensores extends Model
{
	protected $table = 'sensores';
	protected $primaryKey = 'id_sensor';

	protected $fillable = [
		'nombre',
		'bus',
		'address',
		'tasa_flujo',
		'modo_salida'
	];

	public function config_sensores()
	{
		return $this->hasMany(ConfigSensores::class, 'id_sensor');
	}

	public function mediciones()
	{
		return $this->hasMany(Mediciones::class, 'id_sensor');
	}
}
