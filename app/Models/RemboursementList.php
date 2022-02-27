<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property integer id
 * @property integer user_id
 * @property integer reason
 * @property integer week_number
 * @property integer montant
 * @property string service
 * @method static where(string $string, $id)
 * @method static orderByDesc(string $string)
 */
class RemboursementList extends Model
{
    use HasFactory;

    protected $table = 'RemboursementLists';
    protected $fillable = ['user_id', 'id', 'reason', 'montant'];
    protected $casts= [
        'created_at'=>'datetime:d/m/Y H:I',
        'accepted'=>'bool'
    ];


    public function getUser(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getItem(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ObjRemboursement::class, 'item_id')->withTrashed();
    }

    public function GetAdmin(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
