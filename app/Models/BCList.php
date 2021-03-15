<?php

namespace App\Models;

use Faker\Provider\DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BCList
 * @property int id
 * @property int starter_id
 * @property string place
 * @property int type_id
 * @property DateTime started_at
 */
class BCList extends Model
{
    protected $table = "BCLists";
    use HasFactory;
    public function GetUser(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'starter_id');
    }

    public function GetType(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(BCType::class, 'type_id');
    }

    public function GetPatients(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BCPatient::class, 'BC_id');
    }
    public function GetPersonnel(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BCPersonnel::class, 'BC_id');
    }


}
