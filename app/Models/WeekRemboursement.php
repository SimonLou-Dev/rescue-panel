<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/***
 * Class WeekRemboursement
 * @package App\Models
 * @property int id
 * @property int user_id
 * @property int week_number
 * @property int total
 */
class WeekRemboursement extends Model
{
    use HasFactory;
    public function GetUser(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
