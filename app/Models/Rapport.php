<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, mixed $id)
 * @method static find(\phpDocumentor\Reflection\Types\Integer $id)
 */
class Rapport extends Model
{
    use HasFactory;
    protected $fillable = [
        "patientID",
        "InterType",
        "transport",
        "description",
        "prix",
        'ATA_start',
        'ATA_end',
        'id'
    ];

    public function Inter(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(InterType::class, 'InterType');
    }
    public function Patient(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patientID');
    }
    public function facture(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Factures::class, 'rapport_id');
    }
    public function Hospital(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(HospitalList::class, 'transport');
    }

}
