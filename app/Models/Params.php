<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property string type
 * @property string value
 * @method static where(string $string, string $channel)
 */

class Params extends Model
{
    use HasFactory;
    protected $table = 'SiteParameters';


}
