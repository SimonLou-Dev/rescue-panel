<?php

namespace App\Models;

use Faker\Provider\DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * Class BCPatient
 * @package App\Models
 * @property int id
 * @property int BC_id
 * @property boolean idcard
 * @property int rapport_id
 * @property int patient_id
 * @property DateTime added_at
 * @property int blessure_type
 * @property int couleur
 * @property string name
 * */
class BCPatient extends Model
{
    use HasFactory;

    public function GetBC(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(BCList::class, 'BC_ID');
    }
    public function GetRapport(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->HasOne(Rapport::class, 'id','rapport_id');
    }
    public function GetPatient(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->HasOne(Patient::class, 'id','patient_id');
    }
    public function GetBlessure(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Blessure::class, 'blessure_type');
    }
    public function GetColor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CouleurVetement::class, 'couleur');
    }
}
