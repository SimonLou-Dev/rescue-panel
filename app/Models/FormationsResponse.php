<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class FormationsResponse
 * @package App\Models
 * @property int id
 * @property bool finished
 * @property int lastquestion_id
 * @property int user_id
 * @property int note
 * @property int formation_id
 * @method static where(string $column, string $operator = null, mixed $value = null)
 * @method static orderByDesc(string $string)
 *
 */
class FormationsResponse extends Model
{
    use HasFactory;
    protected $table = "FormationResponses";

    protected $fillable = ['finished', 'lastquestion_id', 'user_id', 'note'];

    public function GetUser(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function GetFormation(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Formation::class, 'formation_id');
    }
}
