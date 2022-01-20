<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Intervention
 * @package App\Models
 * @property int id
 * @property string name
 * @property string service
 * @method static where(string $column, string $operator = null, mixed $value = null)
 * @method static orderByDesc(string $string)
 *
 */
class Intervention extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "Interventions";
    protected $fillable = ['name', 'service'];
}
