<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BCPersonnel
 * @package App\Models
 * @property int id
 * @property int user_id
 * @property string name
 * @property int BC_id
 */
class BCPersonnel extends Model
{
    use HasFactory;
    protected $table = "BCPersonnels";
    public function GetUser(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function GetBC(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(BCList::class, 'BC_id');
    }
}
