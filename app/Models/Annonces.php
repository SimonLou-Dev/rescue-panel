<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



/**
 * @property int id
 * @property int discord_msg_id
 * @property string title
 * @property string content
 * @property string posted_at
 * @method static where(string $column, string $operator = null, mixed $value = null)
 * @method static orderByDesc(string $string)
 *
 */
class Annonces extends Model
{

    protected $table = 'Annonces';

    protected $fillable = ['discord_msg', 'title', 'content', 'posted_at'];
    use HasFactory;
}
