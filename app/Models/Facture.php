<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Facture
 * @package App\Models
 * @property int id
 * @property int patient_id
 * @property int rapport_id
 * @property boolean payed
 * @property int price
 * @property int payement_confirm_id
 */
class Facture extends Model
{
    use HasFactory;

    public function GetCofirmUser(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(User::class, 'id', 'payement_confirm_id');
    }
    public function GetPatient(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }
    public function GetRapport(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Rapport::class, 'rapport_id');
    }
}
