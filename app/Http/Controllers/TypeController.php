<?php

namespace App\Http\Controllers;

use App\Models\Type;
use Illuminate\Http\Request;

class TypeController extends Controller
{
    public function index()
    {
        $lateFees = Type::all();
        return view('dashboard.late_fees.index', compact('lateFees'));
    }

    public function create()
    {
        return view('dashboard.late_fees.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'entry_statement_id' => 'required|exists:entry_statements,id',
            'type' => 'required|string',
            'fee' => 'required|numeric',
        ]);

        Type::create($request->all());

        return redirect()->route('late_fees.index');
    }

    public function show(Type $lateFee)
    {
        return view('dashboard.late_fees.show', compact('lateFee'));
    }

    public function edit(Type $lateFee)
    {
        return view('dashboard.late_fees.edit');
    }

    public function update(Request $request, Type $lateFee)
    {
        $request->validate([
            'entry_statement_id' => 'required|exists:entry_statements,id',
            'type' => 'required|string',
            'fee' => 'required|numeric',
        ]);

        $lateFee->update($request->all());

        return redirect()->route('late_fees.index');
    }

    public function destroy(Type $lateFee)
    {
        $lateFee->delete();
        return redirect()->route('late_fees.index');
    }
}
