<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Grade
 * @package App\Models
 * @property int id
 * @property string name
 * @method static where(string $column, string $operator = null, mixed $value = null)
 * @method static orderByDesc(string $string)
 * @method static orderBy(string $column, string $sens)
 *
 */

class Grade extends Model
{
    protected $fillable = ['id'];

    use HasFactory;

    protected $table = "Grades";

    public function isAdmin(){
        return $this->admin;
    }

    public function getUsers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(User::class, 'grade_id');
    }


}
