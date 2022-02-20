<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string reason
 * @property int id
 * @property mixed start_at
 * @property mixed end_at
 * @property int user_id,
 * @property boolean accepted,
 * @property int admin_id
 * @property int discord_msg_id
 */
class AbsencesList extends Model
{
    use HasFactory;
    protected $fillable = ['discord_msg_id','admin_id','accepted','user_id','end_at','start_at','reason'];

    protected $casts = [
        'accepted'=>'boolean',
        'end_at'=>'date:d/m/Y',
        'start_at'=>'date:d/m/Y'
    ];

    public function GetAdmin(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function GetUser(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
