<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use TimeCalculate;

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
    protected $fillable = ['week_number','reason','adder','time_quantity','accepted','admin_id'];

    protected $casts = [
        'adder'=>'boolean',
        'accepted'=>'boolean',
    ];

    protected function timeQuantity():Attribute
    {
        return new Attribute(
            get: fn ($value) => TimeCalculate::secToHours($value, false),
            set: fn ($value) => TimeCalculate::hoursToSec($value, false),
        );
    }

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
