<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntryStatementLog extends Model
{
    protected $fillable = ['user_id', 'entry_statement_id', 'action', 'details'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function entryStatement()
    {
        return $this->belongsTo(EntryStatement::class);
    }
}

