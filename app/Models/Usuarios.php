<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Usuario
 * 
 * @property int $id
 * @property string $perfil_image
 * @property string $nombre
 * @property string $apellido_paterno
 * @property string $apellido_materno
 * @property string $email
 * @property string|null $password
 * @property string $telefono
 * @property Carbon $fecha_registro
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Usuarios extends Model
{
	protected $table = 'usuarios';

	protected $casts = [
		'fecha_registro' => 'datetime'
	];

	protected $hidden = [
		'password'
	];

	protected $fillable = [
		'perfil_image',
		'nombre',
		'apellido_paterno',
		'apellido_materno',
		'email',
		'password',
		'telefono',
		'fecha_registro'
	];
}
