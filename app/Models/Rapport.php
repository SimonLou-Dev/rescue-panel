<?php

namespace App\Models;

use Faker\Provider\DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

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
 * @property int discord_msg_id
 * @property string started_at
 * @method static where(string $column, string $operator = null, mixed $value = null)
 * @method static orderByDesc(string $string)
 *
 */
class Rapport extends Model
{
    use HasFactory, Searchable;
    protected $table = "Rapports";
    protected $fillable = ['user_id', 'interType', 'transport', 'description', 'price', 'ATA_start', 'ATA_end', 'patient_id'];

    public function GetPatient(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function GetUser(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function GetType(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Intervention::class, 'interType')->withTrashed();
    }

    public function GetTransport(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Hospital::class, 'transport')->withTrashed();
    }

    public function GetFacture(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Facture::class, 'rapport_id');
    }
}
