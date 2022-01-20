<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int id
 * @property string name
 * @property string desc
 * @property array stock_item
 */
class Pathology extends Model
{
    use HasFactory;
    protected $table = 'Pathologys';
    protected $casts = [
        'stock_item'=>'array'
    ];
}
