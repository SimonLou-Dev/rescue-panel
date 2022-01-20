<?php

namespace App\Models;

use Faker\Provider\DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

/**
 * Class BCList
 * @property int id
 * @property int starter_id
 * @property string place
 * @property int type_id
 * @property bool ended
 * @property string description
 * @property string caserne
 * @property string service
 *
 * @method static where(string $column, string $operator = null, mixed $value = null)
 * @method static orderByDesc(string $string)
 *
 */
class BCList extends Model
{

    use Searchable, HasFactory;

    protected $table = "BCLists";

    protected $casts = [
        'ended'=>'boolean',
        'created_at'=>'datetime:d/m/Y H:i'
    ];

    protected $fillable = ['starter_id', 'place', 'type_id', 'started_at', 'ended', 'description', 'caserne', 'service'];

    public function GetUser(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'starter_id');
    }

    public function GetType(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(BCType::class, 'type_id')->withTrashed();
    }

    public function GetPatients(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BCPatient::class, 'BC_id');
    }
    public function GetPersonnel(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BCPersonnel::class, 'BC_id');
    }

    public function getScoutKey()
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
           'place'=>$this->place,
           'type'=>$this->GetType->name,
           'service'=>$this->service,
       ];
   }

}
