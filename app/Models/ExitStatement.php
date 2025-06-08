<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExitStatement extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'entry_statement_id',
        'car_number',
        'owner_name',
        'fee_value'
    ];

    public function entryStatement()
    {
        return $this->belongsTo(EntryStatement::class);
    }
}
