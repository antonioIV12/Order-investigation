<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Integration extends Model
{
  // Add this line to allow these fields to be saved
    protected $fillable = [
        'cp_slug',
        'cp_token',
        'cr_user',
        'cr_key'
    ];
}
