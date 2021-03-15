<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, $id)
 * @property int id
 * @property string name
 * @property string vorname
 * @property string tel
 */
class Patient extends Model
{
    use HasFactory;

    public function GetRapports(){
        return $this->hasMany(Rapport::class, 'patient_id');
    }
    public function GetFactures(){
        return $this->hasMany(Facture::class, 'patient_id');
    }
}
