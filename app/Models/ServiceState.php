<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\DocBlock\Tags\Property;

/**
* @property int id
* @property int color
* @property string name
* @method static where(string $column, mixed $value)
* @method static orderByDesc(string $string)
*/
class ServiceState extends Model
{
    use HasFactory;

    protected $table = 'ServiceStates';

}