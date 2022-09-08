<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppModel extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'apps';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'app_name', 
        'app_logo', 
        'play_url', 
        'ads_control', 
        'live_control', 
        'have_play_url', 
        'app_unique_id ', 
        'privacy_policy_link', 
    ];
}
