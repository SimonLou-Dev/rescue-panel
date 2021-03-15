<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * Class BCType
 * @package App\Models
 * @property int id
 * @property string name
 */
class BCType extends Model
{
    use HasFactory;
    protected $table = "BCTypes";
}
