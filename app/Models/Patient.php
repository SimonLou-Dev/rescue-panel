<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

/**
 *
 *
 * @property int id
 * @property string name
 * @property string vorname
 * @property string tel
 * @property mixed naissance
 * @property string living_place
 * @method static where(string $column, string $operator = null, mixed $value = null)
 * @method static orderByDesc(string $string)
 *
 */
class Patient extends Model
{
    use HasFactory, Searchable;
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

    public function getTestsPoudre(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TestPoudre::class, 'patient_id');
    }

    public function toSearchableArray()
    {
        return [
            "id"=>$this->id,
            "name"=>$this->name,
            "vorname"=>$this->vorname,
            "tel"=>$this->tel,
            "naissance"=>$this->naissance,
            "living_place"=>$this->living_place,
        ];
    }

    public function getScoutKey()
    {
        return $this->id;
    }

    public function getScoutKeyName()
    {
        return 'id';
    }
}
