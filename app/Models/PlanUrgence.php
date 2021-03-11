<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, false $false)
 */
class PlanUrgence extends Model
{
    use HasFactory;

    protected $fillable = [
      "starter_id",
      "type",
      "place",
      "started_at",
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'starter_id');
    }

    public function gettype(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(PUTypes::class, "type");
    }
    public function PUPatient(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PlanUrgencePatient::class, 'PU_ID');
    }

    public function PUPersonnel(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PlanUrgencePersonnel::class, 'PU_ID');
    }


}
