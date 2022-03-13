<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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
 * @property int matricule
 * @property int discord_id
 * @property string $name
 * @property string email
 * @property string password
 * @property int fire_grade_id
 * @property int medic_grade_id
 * @property int token
 * @property boolean OnService
 * @property int bc_id
 * @property string liveplace
 * @property string tel
 * @property boolean pilote
 * @property int compte
 * @property string bg_img
 * @property array sanctions
 * @property array materiel
 * @property array note
 * @property array notification_preference
 * @property mixed last_service_update
 * @property boolean moderator
 * @property boolean dev
 * @property boolean medic
 * @property boolean fire
 * @property boolean crossService
 * @property string service
 * @method static where(string $column, string $operator = null, mixed $value = null)
 * @method static orderByDesc(string $string)
 * @method static orderBy(string $column, string $sens)
 *
 */
class User extends Authenticatable
{
    use HasFactory, Searchable;

    protected $casts = [
        'moderator'=>'boolean',
        'dev'=>'boolean',
        'medic'=>'boolean',
        'fire'=>'boolean',
        'crossService'=>'boolean',
        'pilote'=>'boolean',
        'OnService'=>'boolean',
        'sanctions'=>'array',
        'materiel'=>'array',
        'note'=>'array',
        'notification_preference'=>'array',
    ];

    protected $table = "Users";
    protected $fillable = ['grade_id','last_service_update', 'name', 'email', 'password', 'token', 'service', 'liveplace', 'tel', 'pilote', 'compte', 'timezone', 'bg_img','matricule','discord_id','sanctions','materiel','note'];

    protected function discordId():Attribute
    {
        return new Attribute(
            get: fn ($value) => ''.$value,
            set: fn ($value) => (int) $value,
        );
    }


    public function GetRapports(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Rapport::class, 'user_id');
    }
    public function GetMedicGrade(): BelongsTo
    {
        return $this->belongsTo(Grade::class, "medic_grade_id");
    }
    public function GetFireGrade(): BelongsTo
    {
        return $this->belongsTo(Grade::class, "fire_grade_id");
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
    public function GetAllRemboursement(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(RemboursementList::class, 'user_id');
    }
    public function getServiceState(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ServiceState::class, 'serviceState')->withTrashed();
    }

    public function getRequests(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ModifyServiceReq::class, 'user_id');
    }

    public function isAdmin(): bool
    {
        if($this->service === "SAMS"){
            return $this->GetMedicGrade->admin;
        }else if($this->service === "LSCoFD"){
            return $this->GetFireGrade->admin;
        }
        return false;
    }
    public function GetGradePower():int
    {
        if($this->service === "SAMS"){
            return $this->GetMedicGrade->power;
        }else if($this->service === "LSCoFD"){
            return $this->GetFireGrade->power;
        }
        return 0;
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
            "matricule"=>$this->matricule,
            "OnService"=>$this->OnService,
            'service'=>$this->service,
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

    public function getUserGradeInService() : mixed
    {
        if($this->service === "SAMS"){
            return $this->GetMedicGrade;
        }else if($this->service === "LSCoFD"){
            return $this->GetFireGrade;
        }
        return null;
    }

    public function isInFireUnit():bool
    {
        return  $this->fire || ($this->medic && $this->crossService);
    }

    public function isInMedicUnit():bool
    {
        return  $this->medic || ($this->fire && $this->crossService);

    }
}
