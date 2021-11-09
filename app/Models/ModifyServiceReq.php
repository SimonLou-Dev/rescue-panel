<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModifyServiceReq extends Model
{
    use HasFactory;
    protected $table = "ModifyServiceReqs";

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
