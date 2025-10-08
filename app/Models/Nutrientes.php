<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Nutriente
 * 
 * @property int $id_nutriente
 * @property string $nombre
 *
 * @package App\Models
 */
class Nutrientes extends Model
{
	protected $table = 'nutrientes';
	protected $primaryKey = 'id_nutriente';
	public $timestamps = false;

	protected $fillable = [
		'nombre'
	];
}
