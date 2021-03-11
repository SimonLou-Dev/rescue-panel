<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, int $id)
 */
class PUTypes extends Model
{
    use HasFactory;
    protected $fillable= [
        'name'
    ];
}
