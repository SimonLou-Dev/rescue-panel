<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class BCType
 * @package App\Models
 * @property int id
 * @property string name
 * @method static where(string $column, string $operator = null, mixed $value = null)
 * @method static orderByDesc(string $string)
 * @method static all($columns = ['*'])
 *
 */
class BCType extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "BCTypes";

    protected $fillable = ['name'];
}
