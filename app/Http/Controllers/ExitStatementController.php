<?php

namespace App\Http\Controllers;

use App\Models\ExitStatement;
use App\Models\EntryStatement;
use Illuminate\Http\Request;

class ExitStatementController extends Controller
{
    public function index()
    {
        $exitStatements = ExitStatement::all();
        return view('dashboard.exit_statements.index', compact('exitStatements'));
    }

    public function create()
    {
        $entryStatements = EntryStatement::all();
        return view('dashboard.exit_statements.create', compact('entryStatements'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'date' => 'required|date',
            'entry_statement_id' => 'required|exists:entry_statements,id',
            'car_number' => 'required|string',
            'owner_name' => 'required|string',
            'fee_value' => 'required|numeric',
        ]);

        ExitStatement::create($request->all());

        return redirect()->route('dashboard');
    }

    public function show(ExitStatement $exitStatement)
    {
        return view('dashboard.exit_statements.show', compact('exitStatement'));
    }

    public function edit(ExitStatement $exitStatement)
    {
        $entryStatements = EntryStatement::all();
        return view('dashboard.exit_statements.edit', compact('exitStatement', 'entryStatements'));
    }

    public function update(Request $request, ExitStatement $exitStatement)
    {
        $request->validate([
            'date' => 'required|date',
            'entry_statement_id' => 'required|exists:entry_statements,id',
            'car_number' => 'required|string',
            'owner_name' => 'required|string',
            'fee_value' => 'required|numeric',
        ]);

        $exitStatement->update($request->all());

        return redirect()->route('exit_statements.index');
    }

    public function destroy(ExitStatement $exitStatement)
    {
        $exitStatement->delete();
        return redirect()->route('exit_statements.index');
    }
}
