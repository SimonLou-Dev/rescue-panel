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
 * @property string service
 * @property int admin_id
 * @method static where(string $column, string $operator = null, mixed $value = null)
 * @method static orderByDesc(string $string)
 *
 */
class WeekRemboursement extends Model
{
    use HasFactory;

    protected $table = "WeekRemboursements";
    protected $fillable = ['user_id', 'week_number', 'total'];
    public function GetUser(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
