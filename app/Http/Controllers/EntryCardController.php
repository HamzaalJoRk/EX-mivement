<?php

namespace App\Http\Controllers;

use App\Models\EntryCard;
use App\Models\EntryStatement;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class EntryCardController extends Controller
{
    public function print($id)
    {
        $entryCard = EntryCard::findOrFail($id);
        $entry = EntryStatement::findOrFail($entryCard->entry_statement_id);
        $createdAt = Carbon::parse($entry->created_at);
        $weeks = $entry->stay_duration;
        $allowedStay = $createdAt->copy()->addDays($weeks * 7);
        $today = Carbon::now();

        return view('dashboard.entry_statements.entryCard', compact('entry', 'allowedStay', 'createdAt'));
    }
}
