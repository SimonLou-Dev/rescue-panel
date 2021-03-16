<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/***
 * Class WeekService
 * @package App\Models
 * @property int id
 * @property int week_number
 * @property string dimanche
 * @property string lundi
 * @property string mardi
 * @property string mercredi
 * @property string jeudi
 * @property string vendredi
 * @property string samedi
 * @property int user_id
 */
class WeekService extends Model
{
    use HasFactory;
    protected $table = "WeekServices";
    public function GetUser(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
