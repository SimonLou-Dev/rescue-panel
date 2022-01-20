<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/***
 * Class WeekService
 * @package App\Models
 * @property int id
 * @property int user_id
 * @property string service
 * @property int week_number
 * @property string dimanche
 * @property string lundi
 * @property string mardi
 * @property string mercredi
 * @property string jeudi
 * @property string vendredi
 * @property string samedi
 * @property string ajustement
 * @property string total
 * @method static where(string $column, string $operator = null, mixed $value = null)
 * @method static insert(array $datas)
 * @method static orderByDesc(string $string)
 *
 */
class WeekService extends Model
{
    use HasFactory;
    protected $table = "WeekServices";
    protected $fillable = ['week_number', 'dimanche', 'lundi','mardi','mercredi','jeudi','vendredi','samedi','user_id','service','ajustement','total'];
    public function GetUser(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
