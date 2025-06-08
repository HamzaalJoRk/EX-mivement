<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EntranceFee extends Model
{
    use HasFactory;

    protected $fillable = [
        'duration',
        'fees',
        'type'
    ];
}
