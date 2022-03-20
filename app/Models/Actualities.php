<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property int discord_msg_id
 * @property string service
 * @property string content
 * @method static where(string $column, string $operator = null, mixed $value = null)
 * @method static orderByDesc(string $string)
 *
 */
class Actualities extends Model
{
    protected $table = 'Actualities';
    protected $casts = [
        'created_at'=>'datetime:d/m/Y'
    ];

    protected $fillable = ['discord_msg', 'service', 'content','created_at'];
    use HasFactory;
}
