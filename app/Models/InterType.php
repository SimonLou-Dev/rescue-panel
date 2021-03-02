<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, int $id)
 */
class InterType extends Model
{
    use HasFactory;
    protected $fillable= [
        'name'
    ];

    public function Rapport(){
        return $this->hasMany(Rapport::class, 'InterType');
    }
}
