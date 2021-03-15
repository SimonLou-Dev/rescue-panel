<?php

namespace App\Models;

use Faker\Provider\DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Rapport
 * @package App\Models
 * @property int id
 * @property int user_id
 * @property int interType
 * @property int transport
 * @property string description
 * @property int price
 * @property DateTime ATA_start
 * @property DateTime ATA_end
 * @property int patient_id
 */
class Rapport extends Model
{
    use HasFactory;
    protected $table = "Rapports";

    public function GetPatient(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }
    public function GetType(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Intervention::class, 'interType');
    }
    public function GetTransport(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Hospital::class, 'transport');
    }
}
