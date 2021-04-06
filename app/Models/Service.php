<?php

namespace App\Models;

use Faker\Provider\DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Service
 * @package App\Model
 * @property int id
 * @property int user_id
 * @property DateTime started_at
 * @property DateTime ended_at
 * @property string total
 * @method static where(string $column, mixed $value)
 * @method static orderByDesc(string $string)
 *
 */
class Service extends Model
{
    use HasFactory;
    protected $table = "Services";
    protected $fillable = ['user_id', 'started_at', 'ended_at', 'total'];

    public function GetUser(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
