<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class LieuxSurvol
 * @package App\Models
 * @property int id
 * @property string name
 */
class LieuxSurvol extends Model
{
    use HasFactory;
    protected $table = "LieuxSurvols";
}
