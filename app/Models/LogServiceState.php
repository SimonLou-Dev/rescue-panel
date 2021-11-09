<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogServiceState extends Model
{
    use HasFactory;

    protected $table = 'ServiceStatesLogs';
    protected $fillable = ['user_id', 'state_id', 'ended','total','created_at','updated_at','ended'];

    /**
     * @return BelongsTo
     */
    public function GetState(): BelongsTo
    {
        return $this->belongsTo(ServiceState::class, 'state_id');
    }

    /**
     * @return BelongsTo
     */
    public function GetUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
