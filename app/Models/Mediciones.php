<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Medicione
 * 
 * @property int $id_medicion
 * @property int $id_sensor
 * @property int $valor
 * @property Carbon $fecha
 * 
 * @property Sensores $sensore
 *
 * @package App\Models
 */
class Mediciones extends Model
{
	protected $table = 'mediciones';
	protected $primaryKey = 'id_medicion';
	public $timestamps = false;

	protected $casts = [
		'id_sensor' => 'int',
		'valor' => 'int',
		'fecha' => 'datetime'
	];

	protected $fillable = [
		'id_sensor',
		'valor',
		'fecha'
	];

	public function sensore()
	{
		return $this->belongsTo(Sensores::class, 'id_sensor');
	}
}
