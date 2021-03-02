<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanUrgencePersonnel extends Model
{
    use HasFactory;
    protected $fillable = [
        'userID',
        'PU_ID',
    ];

    public function GetUser(){
        return $this->belongsTo(User::class, 'userID');
    }


}
