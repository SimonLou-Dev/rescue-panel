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
 */
class Annonces extends Model
{
    use HasFactory;
}
