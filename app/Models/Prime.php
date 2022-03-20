<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

/**
 * @property string reason
 * @property int id
 * @property int montant
 * @property int user_id
 * @property int week_number,
 * @property boolean accepted,
 * @property string service
 * @property int admin_id
 * @property int discord_msg_id
 */
class Prime extends Model
{
    use HasFactory;
    protected $table = "Primes";

    protected $casts = [
        'accepted'=>'boolean',
        'created_at'=>'datetime:d/m/Y H:i'
    ];



    public function getUser(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }




    public function GetAdmin(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

}
