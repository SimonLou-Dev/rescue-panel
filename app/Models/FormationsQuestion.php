<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Psy\Util\Json;


/**
 * Class FormationsQuestion
 * @package App\Models
 * @property int id
 * @property int formation_id
 * @property string type
 * @property string correction
 * @property int max_note
 * @property json responses
 * @property json right_responses
 * @property string name
 * @property string desc
 * @property string img
 * @method static where(string $column, string $operator = null, mixed $value = null)
 * @method static orderByDesc(string $string)
 *
 */
class FormationsQuestion extends Model
{
    use HasFactory;

    protected $table = "FormationsQuestions";

    protected $fillable = ['formation_id', 'type', 'correction', 'max_note', 'responses', 'right_responses', 'name', 'desc', 'img'];

    public function GetFormation(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Formation::class, 'formation_id');
    }
}
