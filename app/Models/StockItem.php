<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string name
 * @property int montant
 * @property int id
 */
class StockItem extends Model
{
    use HasFactory, SoftDeletes;
}
