<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Violation extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'fee'
    ];

    public function entryStatements()
    {
        return $this->belongsToMany(EntryStatement::class);
    }

}
