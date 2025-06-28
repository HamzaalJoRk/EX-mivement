<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntryCard extends Model
{
    use HasFactory;

    protected $fillable = [
        'entry_statement_id',
        'owner_name',
        'car_number',
        'car_type',
        'stay_duration',
        'entry_date',
        'exit_date',
        'qr_code',
    ];

    public function entryStatement()
    {
        return $this->belongsTo(EntryStatement::class);
    }

}
