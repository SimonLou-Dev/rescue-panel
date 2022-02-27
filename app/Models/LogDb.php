<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class LogDb extends Model
{
    use HasFactory, Searchable;
    protected $table = 'Logs';
    protected $casts = [
        'created_at'=>'datetime:d/m/Y H:i'
    ];
}
