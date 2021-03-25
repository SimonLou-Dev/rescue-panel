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
 * @method static where(string $column, string $operator = null, mixed $value = null)
 * @method static orderByDesc(string $string)
 *
 */
class BCList extends Model
{
    protected $table = "BCLists";

    protected $fillable = ['starter_id', 'place', 'type_id', 'started_at'];
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
