<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

/**
 * Class TestPoudre
 * @package App\Models
 * @property int id
 * @property int user_id
 * @property int patient_id
 * @property bool on_clothes_positivity
 * @property bool on_skin_positivity
 * @property string lieux_prelevement
 */
class TestPoudre extends Model
{
    use HasFactory, Searchable;
    protected $table = 'PouderTests';
    protected $fillable = ['on_clothes_positivity', 'on_clothes_positivity', 'lieux_prelevement', 'patient_id', 'user_id'];

    public function GetPatient(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function GetPersonnel(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
