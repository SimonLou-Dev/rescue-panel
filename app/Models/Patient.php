<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, string $string1, string $string2)
 * @property mixed id
 * @property mixed tel
 * @property mixed name
 */
class Patient extends Model
{
    use HasFactory;
    protected $fillable=  [
        "tel",
        "name",
        "vorname",
    ];

    public function inters(){
        return $this->hasMany(Rapport::class, 'patientID');
    }

}


