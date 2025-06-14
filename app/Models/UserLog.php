<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class UserLog extends Model
{
    protected $fillable = ['user_id', 'action', 'details', 'ip', 'device'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

