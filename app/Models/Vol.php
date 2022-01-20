<?php

namespace App\Models;

use Faker\Provider\DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

/**
 * Class Vol
 * @package App\Models
 * @property int id
 * @property DateTime decollage
 * @property string raison
 * @property int pilote_id
 * @property int lieux_id
 * @property string service
 * @method static where(string $column, string $operator = null, mixed $value = null)
 * @method static orderByDesc(string $string)
 *
 */
class Vol extends Model
{
    use HasFactory, Searchable;
    protected $table = "Vols";
    protected $fillable = ['decollage', 'raison', 'pilote', 'lieux_id', 'service'];
    protected $casts = [
        'decollage'=>'datetime:d/m/Y H:i',
        'created_at'=>'datetime:d/m/Y H:i',
    ];

    public function GetLieux(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(LieuxSurvol::class, 'lieux_id')->withTrashed();
    }
    public function GetUser(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'pilote_id');
    }

    public function toSearchableArray()
    {
        return [
            "id"=>$this->id,
            "pilote"=>$this->GetUser->name,
            "lieux"=>$this->GetLieux->name,
            "decollage"=>$this->decollage
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
