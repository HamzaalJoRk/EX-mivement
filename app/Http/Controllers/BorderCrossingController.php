<?php

namespace App\Http\Controllers;

use App\Models\BorderCrossing;
use Illuminate\Http\Request;

class BorderCrossingController extends Controller
{
    public function index()
    {
        $borderCrossings = BorderCrossing::all();
        return view('dashboard.border_crossing.index', compact('borderCrossings'));
    }

    public function create()
    {
        return view('dashboard.border_crossing.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        BorderCrossing::create($request->all());

        return redirect()->route('border_crossing.index');
    }

    public function show(BorderCrossing $borderCrossing)
    {
        return view('dashboard.borderCrossing.show', compact('borderCrossing'));
    }

    public function edit(BorderCrossing $borderCrossing)
    {
        return view('dashboard.border_crossing.edit', compact('borderCrossing'));
    }

    public function update(Request $request, BorderCrossing $borderCrossing)
    {
        $request->validate([
            'name' => 'string',
        ]);

        $borderCrossing->update($request->all());

        return redirect()->route('border_crossing.index');
    }

    public function destroy(BorderCrossing $borderCrossing)
    {
        $borderCrossing->delete();
        return redirect()->route('border_crossing.index');
    }
}
