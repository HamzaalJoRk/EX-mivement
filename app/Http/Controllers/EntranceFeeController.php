<?php

namespace App\Http\Controllers;


use App\Models\EntranceFee;
use App\Models\EntryStatement;
use Illuminate\Http\Request;

class EntranceFeeController extends Controller
{
    public function index()
    {
        $entranceFees = EntranceFee::all();
        return view('dashboard.entrance_fees.index', compact('entranceFees'));
    }

    public function create()
    {
        $entryStatements = EntryStatement::all();
        return view('dashboard.entrance_fees.create', compact('entryStatements'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'duration' => 'required|string',
            'type' => 'required|integer',
            'fees' => 'required|numeric',
        ]);

        EntranceFee::create($request->all());

        return redirect()->route('entrance-fees.index');
    }

    public function show(EntranceFee $entranceFee)
    {
        return view('dashboard.entrance_fees.show', compact('entranceFee'));
    }

    public function edit(EntranceFee $entranceFee)
    {
        $entryStatements = EntryStatement::all();
        return view('dashboard.entrance_fees.edit', compact('entranceFee', 'entryStatements'));
    }

    public function update(Request $request, EntranceFee $entranceFee)
    {
        $request->validate([
            'duration' => 'required|string',
            'type' => 'required|integer',
            'fees' => 'required|numeric',
        ]);

        $entranceFee->update($request->all());

        return redirect()->route('entrance-fees.index');
    }

    public function destroy(EntranceFee $entranceFee)
    {
        $entranceFee->delete();
        return redirect()->route('entrance-fees.index');
    }
}
