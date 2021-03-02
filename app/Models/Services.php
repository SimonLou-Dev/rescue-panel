<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed Started_at
 * @property integer UserId
 * @property mixed EndedAt
 * @property mixed Total
 *
 * @method static where(string $string, $id)
 */
class Services extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'UserId',
        'Started_at',
        'EndedAt',
        'Total',
    ];

    public function getUser(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'UserId');
    }
}
