<?php

namespace App\Http\Controllers;

use App\Models\EntryStatementAdditionalFee;
use Illuminate\Http\Request;

class EntryStatementAdditionalFeeController extends Controller
{
    public function store(Request $request, $entryId)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'fee' => 'required|numeric|min:0',
        ]);

        EntryStatementAdditionalFee::create([
            'entry_statement_id' => $entryId,
            'title' => $request->title,
            'fee' => $request->fee,
        ]);

        return redirect()->back()->with('success', 'تمت إضافة الترسيم بنجاح.');
    }

    public function update(Request $request, $entryId, EntryStatementAdditionalFee $fee)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'fee' => 'required|numeric|min:0',
        ]);

        $fee->update([
            'title' => $request->title,
            'fee' => $request->fee,
        ]);

        return redirect()->back()->with('success', 'تم تعديل الترسيم بنجاح.');
    }

    public function destroy($entryId, EntryStatementAdditionalFee $fee)
    {
        $fee->delete();
        return redirect()->back()->with('success', 'تم حذف الترسيم بنجاح.');
    }

}
