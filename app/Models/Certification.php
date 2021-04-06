<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Certification
 * @package App\Models
 * @property int id
 * @property int user_id
 * @property int formation_id
 * @property int certif_id
 * @method static where(string $column, string $operator = null, mixed $value = null)
 * @method static orderByDesc(string $string)
 *
 */
class Certification extends Model
{
    use HasFactory;
    protected $table = "Certifications";

    protected $fillable = ['user_id', 'formation_id'];

    public function GetUser(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function GetFormation(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Formation::class, 'formation_id');
    }

    public function GetCetif(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ListCertification::class, 'certif_id');
    }
}
