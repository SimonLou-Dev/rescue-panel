<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed|string total
 * @method static where(string $string, string $string1, int $int)
 * @method static insert(array $datas)
 */
class DayService extends Model
{
    use HasFactory;
    protected $fillable = [
        'week',
        'user_id',
        'dimanche',
        'lundi',
        'mardi',
        'mercredi',
        'jeudi',
        'vendredi',
        'samedi',
        'total',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
