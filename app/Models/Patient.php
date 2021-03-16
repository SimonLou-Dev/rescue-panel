<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *
 *
 * @property int id
 * @property string name
 * @property string vorname
 * @property string tel
 * @method static where(string $column, string $operator = null, mixed $value = null)
 * @method static orderByDesc(string $string)
 *
 */
class Patient extends Model
{
    use HasFactory;
    protected $table = "Patients";
    protected $fillable = ['name', 'vorname', 'tel'];

    public function GetRapports(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Rapport::class, 'patient_id');
    }
    public function GetFactures(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Facture::class, 'patient_id');
    }
}
