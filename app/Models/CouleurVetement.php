<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CouleurVetement
 * @package App\Models
 * @property int id
 * @property string name
 */
class CouleurVetement extends Model
{
    use HasFactory;
    protected $table = "CouleurVetements";
}
