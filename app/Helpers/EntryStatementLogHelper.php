<?php

namespace App\Helpers;

use App\Models\EntryStatementLog;
use Illuminate\Support\Facades\Auth;

class EntryStatementLogHelper
{
    public static function log($entryStatementId, $action, $details = null)
    {
        EntryStatementLog::create([
            'user_id' => Auth::id(),
            'entry_statement_id' => $entryStatementId,
            'action' => $action,
            'details' => $details,
        ]);
    }
}
