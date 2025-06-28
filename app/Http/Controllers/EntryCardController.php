<?php

namespace App\Http\Controllers;

use App\Models\EntryCard;
use Illuminate\Http\Request;

class EntryCardController extends Controller
{
    public function print($id)
    {
        $entryCard = EntryCard::findOrFail($id);

        return view('dashboard.entry_statements.entryCard', compact('entryCard'));
    }

}
