<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

/**
 * @property int item_id
 * @property int id
 * @property int user_id
 * @property int week_number,
 * @property boolean accepted,
 * @property string service
 * @property int admin_id
 */
class Prime extends Model
{
    use HasFactory;
    protected $table = "Primes";

    protected $casts = [
        'accepted'=>'boolean',
    ];

    public function getItem(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(PrimeItem::class, 'item_id')->withTrashed();
    }

    public function getUser(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function GetAdmin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

}
