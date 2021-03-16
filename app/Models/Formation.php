<?php

namespace App\Models;

use Carbon\Traits\Timestamp;
use Faker\Provider\DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Formation
 * @package App\Models
 * @property int id
 * @property int creator_id
 * @property int try
 * @property int success
 * @property int average_note
 * @property int max_note
 * @property bool question_timed
 * @property bool timed
 * @property bool unic_try
 * @property bool correction
 * @property bool save_on_deco
 * @property Timestamp timer
 * @property string name
 * @property string image
 * @property string desc
 * @property bool public
 * @property bool can_retry_later
 * @property DateTime time_btw_try
 * @property bool max_try
 * @method static where(string $column, string $operator = null, mixed $value = null)
 * @method static orderByDesc(string $string)
 *
 *
 */
class Formation extends Model
{
    use HasFactory;
    protected $table = "Formations";

    protected $fillable = [
        'creator_id',
        'try',
        'success',
        'average_note',
        'max_note',
        'question_timed',
        'timed',
        'unic_try',
        'correction',
        'save_on_deco',
        'timer',
        'name',
        'image',
        'desc',
        'public',
        'can_retry_later',
        'time_btw_try',
        'max_try'
    ];

    public function GetCreator(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(User::class, 'id', 'creator_id');
    }
    public function GetCertifications(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Certification::class, 'formation_id');
    }
    public function GetResponses(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(FormationsResponse::class, 'formation_id');
    }
    public function GetQuestions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(FormationsQuestion::class, 'formation_id');
    }
}
