<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class RegistroMedicione
 * 
 * @property int $id_regis_med
 * @property float $ph_value
 * @property float $ce_value
 * @property float $tagua_value
 * @property float $us_value
 * @property float $tam_value
 * @property float $hum_value
 * @property Carbon $fecha
 *
 * @package App\Models
 */
class RegistroMediciones extends Model
{
	protected $table = 'registro_mediciones';
	protected $primaryKey = 'id_regis_med';
	public $timestamps = false;

	protected $casts = [
		'ph_value' => 'float',
		'ce_value' => 'float',
		'tagua_value' => 'float',
		'us_value' => 'float',
		'tam_value' => 'float',
		'hum_value' => 'float',
		'fecha' => 'datetime'
	];

	protected $fillable = [
		'ph_value',
		'ce_value',
		'tagua_value',
		'us_value',
		'tam_value',
		'hum_value',
		'fecha'
	];
}
