<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use phpDocumentor\Reflection\DocBlock\Tags\Property;

/**
 * @property int $id
 * @property int $bc_id
 * @property int $property_number
 * @property string $compte
 * @property int $type_id
 */
class FireReport extends Model
{
    use HasFactory, Searchable;

    protected $fillable = ['bc_id', 'property_number', 'compte', 'type_id','created_at'];

    protected $casts = [
        'created_at'=>'datetime:d/m/Y H:i'
    ];

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
            'property_number'=>$this->property_number,
            'type'=>$this->GetType->name,
            'compte'=>$this->compte,
        ];
    }

    public function GetType(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(FireReportType::class, 'id','type_id');
    }

    public function GetBC(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(BCList::class, 'bc_id');
    }


}
