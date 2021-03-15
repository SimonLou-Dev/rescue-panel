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
 */
class Vol extends Model
{
    use HasFactory;

    public function GetLieux(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(LieuxSurvol::class, 'lieux_id');
    }
    public function GetUser(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'pilote_id');
    }
}
