<?php

namespace App\Http\Controllers;

use App\Models\LateFee;
use App\Models\EntryStatement;
use Illuminate\Http\Request;

class LateFeeController extends Controller
{
    public function index()
    {
        $lateFees = LateFee::all();
        return view('dashboard.late_fees.index', compact('lateFees'));
    }

    public function create()
    {
        $entryStatements = EntryStatement::all();
        return view('dashboard.late_fees.create', compact('entryStatements'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'entry_statement_id' => 'required|exists:entry_statements,id',
            'type' => 'required|string',
            'fee' => 'required|numeric',
        ]);

        LateFee::create($request->all());

        return redirect()->route('late_fees.index');
    }

    public function show(LateFee $lateFee)
    {
        return view('dashboard.late_fees.show', compact('lateFee'));
    }

    public function edit(LateFee $lateFee)
    {
        $entryStatements = EntryStatement::all();
        return view('dashboard.late_fees.edit', compact('lateFee', 'entryStatements'));
    }

    public function update(Request $request, LateFee $lateFee)
    {
        $request->validate([
            'entry_statement_id' => 'required|exists:entry_statements,id',
            'type' => 'required|string',
            'fee' => 'required|numeric',
        ]);

        $lateFee->update($request->all());

        return redirect()->route('late_fees.index');
    }

    public function destroy(LateFee $lateFee)
    {
        $lateFee->delete();
        return redirect()->route('late_fees.index');
    }
}
