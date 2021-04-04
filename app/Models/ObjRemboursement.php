<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/***
 * Class ObjRemboursement
 * @package App\Models
 * @property int id
 * @property string name
 * @property int price
 * @method static where(string $column, string $operator = null, mixed $value = null)
 * @method static orderByDesc(string $string)
 *
 */
class ObjRemboursement extends Model
{
    use HasFactory;
    protected $table = "ObjRemboursements";
    protected $fillable = ['name', 'price', 'id'];
}
