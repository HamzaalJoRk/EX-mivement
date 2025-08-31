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
        $stayDuration = $entry->stay_duration;
        $stayType = $entry_statement->stay_type ?? 'week';
        if ($stayDuration == 2) {
            $stayType = 'week';
        } else {
            $stayType = 'month';
        }
        // تحديد التاريخ المسموح
        if ($stayType === 'month') {
            $allowedStay = $createdAt->copy()->addMonths($stayDuration / 4);
        } else {
            $allowedStay = $createdAt->copy()->addDays($stayDuration * 7);
        }
        // $allowedStay = $createdAt->copy()->addDays($weeks * 7);
        $today = Carbon::now();

        return view('dashboard.entry_statements.entryCard', compact('entry', 'allowedStay', 'createdAt'));
    }
}
