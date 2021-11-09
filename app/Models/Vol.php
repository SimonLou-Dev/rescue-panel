<?php

namespace App\Models;

use Faker\Provider\DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Vol
 * @package App\Models
 * @property int id
 * @property DateTime decollage
 * @property string raison
 * @property int pilote_id
 * @property int lieux_id
 * @method static where(string $column, string $operator = null, mixed $value = null)
 * @method static orderByDesc(string $string)
 *
 */
class Vol extends Model
{
    use HasFactory;
    protected $table = "Vols";
    protected $fillable = ['decollage', 'raison', 'pilote', 'lieux_id'];

    public function GetLieux(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(LieuxSurvol::class, 'lieux_id')->withTrashed();
    }
    public function GetUser(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'pilote_id');
    }
}
