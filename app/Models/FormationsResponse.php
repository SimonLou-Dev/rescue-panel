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
 */
class FormationsResponse extends Model
{
    use HasFactory;
    protected $table = "FormationResponses";

    public function GetUser(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function GetFormation(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Formation::class, 'formation_id');
    }
}
