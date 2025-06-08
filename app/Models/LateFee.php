<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LateFee extends Model
{
    use HasFactory;

    protected $fillable = [
        'entry_statement_id',
        'type',
        'fee'
    ];

    public function entryStatement()
    {
        return $this->belongsTo(EntryStatement::class);
    }
}
