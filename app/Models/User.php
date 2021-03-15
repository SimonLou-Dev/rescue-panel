<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class User
 * @package App\Models
 * @property int id
 * @property int grade_id
 * @property string name
 * @property string email
 * @property string password
 * @property string token
 * @property bool service
 * @property string liveplace
 * @property int tel
 * @property bool pilote
 * @property int compte
 * @property string timezone
 * @property string bg_img
 */
class User extends Authenticatable
{
    use HasFactory;
    protected $table = "Users";

    public function GetRapports(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Rapport::class, 'user_id');
    }
    public function GetGrade(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Grade::class, 'grade_id');
    }
    public function GetWeekServices(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(WeekService::class, 'user_id');
    }
    public function GetServices(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Service::class, 'user_id');
    }
    public function GetVol(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Vol::class, 'pilote_id');
    }
    public function GetRemboursement(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(WeekRemboursement::class, 'user_id');
    }
}
