<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Scout\Searchable;
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
 * @method static orderBy(string $column, string $sens)
 *
 */
class User extends Authenticatable
{
    use HasFactory, Searchable;
    protected $table = "Users";
    protected $fillable = ['grade_id','last_service_update', 'name', 'email', 'password', 'token', 'service', 'liveplace', 'tel', 'pilote', 'compte', 'timezone', 'bg_img','matricule','discord_id','sanctions','materiel','note'];

    public function GetRapports(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Rapport::class, 'user_id');
    }
    public function GetGrade(): BelongsTo
    {
        return $this->belongsTo(Grade::class, "grade_id");
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

    public function isAdmin()
    {
        return $this->GetGrade;
    }
    public function GetGradePower():bool
    {
        return $this->GetGrade->power;
    }

    public function toSearchableArray()
    {
        return [
            "id"=>$this->id,
            "name"=>$this->name,
            "discord_id"=>$this->discord_id,
            "grade_id"=>$this->grade_id,
            "tel"=>$this->tel,
            "compte"=>$this->compte,
            "matricule"=>$this->matricule
        ];
    }

    public function getScoutKey()
    {
        return $this->id;
    }

    public function getScoutKeyName()
    {
        return 'id';
    }
}
