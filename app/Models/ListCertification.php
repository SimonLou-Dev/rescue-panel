<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ListCertification
 * @package App\Models
 * @property string name
 * @method static where(string $column, string $operator = null, mixed $value = null)
 * @method static orderByDesc(string $string)
 */
class ListCertification extends Model
{
    use HasFactory;

    protected $table = 'list_certifications';

    protected $fillable = ['name'];

    public function GetFormation(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Formation::class, 'certif_id');
    }

    public function GetCertifications(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Certification::class, 'certif_id');
    }
}
