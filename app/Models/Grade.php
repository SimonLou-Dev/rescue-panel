<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

/**
 * Class Grade
 * @package App\Models
 * @property int id
 * @property string name
 * @property boolean admin
 * @property boolean default
 * @property int power
 * @property int discord_role_id
 * @method static where(string $column, string $operator = null, mixed $value = null)
 * @method static orderByDesc(string $string)
 * @method static orderBy(string $column, string $sens)
 *
 */

class Grade extends Model
{
    protected $fillable = ['id'];

    protected $casts = [
      'admin'=>'boolean',
      'default'=>'boolean'
    ];

    use HasFactory, Searchable;

    protected $table = "Grades";

    public function isAdmin(){
        return $this->admin;
    }

    public function getUsers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(User::class, 'grade_id');
    }

    public function getScoutKey()
    {
        return $this->id;
    }

    public function getScoutKeyName()
    {
        return 'id';
    }

    public function toSearchableArray()
    {
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'power'=>$this->power
        ];
    }


}
