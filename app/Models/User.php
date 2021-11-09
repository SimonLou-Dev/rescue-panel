<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Psy\Util\Json;

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
 * @property int serviceState
 * @property integer matricule
 * @property integer discord_id
 * @property json sanctions
 * @property json materiel
 * @property json note
 * @property int last_service_update
 * @method static where(string $column, string $operator = null, mixed $value = null)
 * @method static orderByDesc(string $string)
 *
 */
class User extends Authenticatable
{
    use HasFactory;
    protected $table = "Users";
    protected $fillable = ['grade_id','last_service_update', 'name', 'email', 'password', 'token', 'service', 'liveplace', 'tel', 'pilote', 'compte', 'timezone', 'bg_img','matricule','discord_id','sanctions','materiel','note'];

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
    public function GetCertifications(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Certification::class, 'user_id')->orderBy('formation_id');
    }
    public function GetAllRemboursement(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(RemboursementList::class, 'user_id');
    }
    public function getResponses(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(FormationsResponse::class, 'user_id');
    }
    public function getServiceState(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ServiceState::class, 'serviceState')->withTrashed();
    }

    public function getRequests(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ModifyServiceReq::class, 'user_id');
    }
}
