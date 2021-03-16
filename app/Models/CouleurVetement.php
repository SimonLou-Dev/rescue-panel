<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CouleurVetement
 * @package App\Models
 * @property int id
 * @property string name
 * @method static where(string $column, string $operator = null, mixed $value = null)
 * @method static orderByDesc(string $string)
 *
 */
class CouleurVetement extends Model
{
    use HasFactory;
    protected $table = "CouleurVetements";

    protected $fillable = ['name'];
}
