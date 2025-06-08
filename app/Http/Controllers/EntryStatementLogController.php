<?php

namespace App\Http\Controllers;

use App\Models\EntryStatement;
use App\Models\EntryStatementLog;
use Illuminate\Http\Request;

class EntryStatementLogController extends Controller
{

    public function showLogs($entryId)
    {
        $logs = EntryStatementLog::where('entry_statement_id', $entryId)
            ->with('user')
            ->latest()
            ->get();
        $serial_number = EntryStatement::findOrFail($entryId)->serial_number;

        return view('dashboard.logs.entry_logs', compact('logs', 'entryId', 'serial_number'));
    }

}
