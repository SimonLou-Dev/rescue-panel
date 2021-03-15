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
 */
class Service extends Model
{
    use HasFactory;

    public function GetUser(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
