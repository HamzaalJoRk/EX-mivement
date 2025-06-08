<?php

namespace App\Http\Controllers;

use App\Models\Violation;
use App\Models\EntryStatement;
use Illuminate\Http\Request;

class ViolationController extends Controller
{
    public function index()
    {
        $violations = Violation::all();
        return view('dashboard.violations.index', compact('violations'));
    }

    public function create()
    {
        $entryStatements = EntryStatement::all();
        return view('dashboard.violations.create', compact('entryStatements'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'fee' => 'required|numeric',
        ]);

        Violation::create($request->all());

        return redirect()->route('violations.index');
    }

    public function show(Violation $violation)
    {
        return view('dashboard.violations.show', compact('violation'));
    }

    public function edit(Violation $violation)
    {
        $entryStatements = EntryStatement::all();
        return view('dashboard.violations.edit', compact('violation', 'entryStatements'));
    }

    public function update(Request $request, Violation $violation)
    {
        $request->validate([
            'title' => 'required|string',
            'fee' => 'required|numeric',
        ]);

        $violation->update($request->all());

        return redirect()->route('violations.index');
    }

    public function destroy(Violation $violation)
    {
        $violation->delete();
        return redirect()->route('violations.index');
    }
}
