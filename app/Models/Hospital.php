<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Hospital
 * @package App\Models
 * @property int id
 * @property string name
 * @property string service
 * @method static where(string $column, string $operator = null, mixed $value = null)
 * @method static orderByDesc(string $string)
 *
 */
class Hospital extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "Hospitals";
    protected $fillable = ['name', 'service'];
}
