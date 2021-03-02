<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, $id)
 */
class PlanUrgencePatient extends Model
{
    use HasFactory;
    protected $fillable = [
        "patient_name",
        "rapport_id",
        "PU_ID",
        "addat"
    ];


}
