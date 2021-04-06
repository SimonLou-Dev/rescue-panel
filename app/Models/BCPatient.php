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
 * @method static where(string $column, string $operator = null, mixed $value = null)
 * @method static orderByDesc(string $string)
 *
 * */
class BCPatient extends Model
{
    use HasFactory;
    protected $table = "BCPatients";

    protected $fillable = ['BC_id', 'idcard', 'rapport_id', 'patient_id', 'added_at', 'blessure_type', 'couleur', 'name'];

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
