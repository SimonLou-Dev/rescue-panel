<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int id
 * @property int week_number
 * @property string reason
 * @property boolean adder
 * @property int time_quantity
 * @property boolean accepted
 * @property int admin_id
 */
class ModifyServiceReq extends Model
{
    use HasFactory;
    protected $table = "ModifyServiceReqs";

    protected $casts = [
        'adder'=>'boolean',
        'accepted'=>'boolean',
    ];

    /**
     * @return BelongsTo
     */
    public function GetUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return BelongsTo
     */
    public function GetAdmin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

}
