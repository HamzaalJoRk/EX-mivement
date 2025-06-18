<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EntryStatementAdditionalFee extends Model
{
    protected $fillable = [
        'entry_statement_id',
        'title',
        'fee',
        'isCompleteFinance',
    ];

    public function entryStatement()
    {
        return $this->belongsTo(EntryStatement::class);
    }
}