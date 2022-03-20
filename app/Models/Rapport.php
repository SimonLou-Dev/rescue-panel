<?php

namespace App\Models;

use DateTimeInterface;
use Faker\Provider\DateTime;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use TimeCalculate;

/**
 * Class Rapport
 * @package App\Models
 * @property int id
 * @property int user_id
 * @property int interType
  * @property int patient_id
 * @property int transport
 * @property string description
 * @property int price
 * @property int ata
 * @property int pathology_id
 * @property int discord_msg_id
 * @property string started_at
 * @property string service
 * @property mixed created_at
 * @method static where(string $column, string $operator = null, mixed $value = null)
 * @method static orderByDesc(string $string)
 *
 */
class Rapport extends Model
{
    use HasFactory, Searchable;
    protected $table = "Rapports";
    protected $guarded = [];
    protected $fillable = ['user_id', 'interType', 'discord_msg_id','transport', 'description', 'price', 'ata', 'ATA_end', 'patient_id','discord_msg_id','service','started_at'];
    protected array $cast = [
        'created_at'=>'datetime:d/m/Y H:i',
        'started_at'=>'datetime:d/m/Y H:i',
    ];

    protected function ata():Attribute
    {
        return new Attribute(
            get: fn ($value) => TimeCalculate::secToString($value),
            set: fn ($value) => TimeCalculate::stringToSec($value),
        );
    }

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('d/m/Y H:i');
    }

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

    public function GetPathology(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Pathology::class, 'pathology_id');
    }


    public function getScoutKey(): int
    {
        return $this->id;
    }
    public function getScoutKeyName()
    {
        return 'id';
    }
    public function toSearchableArray()
    {
        return [
            'id'=>$this->id,
            'patient'=>$this->GetPatient->name,
            'service'=>$this->service,
            'created_at'=>$this->created_at,
        ];
    }
}
