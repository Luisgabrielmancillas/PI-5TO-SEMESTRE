<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SeleccionHortaliza
 * 
 * @property int $id_hortaliza
 * @property string $nombre
 * @property bool|null $seleccion
 * @property Carbon $fecha
 * 
 * @property Collection|ConfigSensores[] $config_sensores
 *
 * @package App\Models
 */
class SeleccionHortalizas extends Model
{
	protected $table = 'seleccion_hortalizas';
	protected $primaryKey = 'id_hortaliza';
	public $timestamps = false;

	protected $casts = [
		'seleccion' => 'bool',
		'fecha' => 'datetime'
	];

	protected $fillable = [
		'nombre',
		'seleccion',
		'fecha'
	];

	public function config_sensores()
	{
		return $this->hasMany(ConfigSensores::class, 'id_hortaliza');
	}

	public function scopeSeleccionada($query)
    {
        return $query->where('seleccion', 1);
    }

    public function mediciones()
    {
        return $this->hasMany(RegistroMediciones::class, 'id_hortaliza', 'id_hortaliza');
    }
}
