<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{

    public function index()
    {
        $permissions = Permission::all(); // Get all permissions
        return view('permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('permissions.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:permissions,name',
        ]);
        $permission = Permission::create(['name' => $validatedData['name']]);
        return redirect('/permissions')->with('success', 'Permission created successfully.');
    }

    public function edit(Permission $permission)
    {
        return view('permissions.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        $validatedData = $request->validate([
            'name' => 'required|unique:permissions,name,' . $permission->id,
        ]);
        $permission->update(['name' => $validatedData['name']]);
        return redirect('/permissions')->with('success', 'Permission updated successfully.');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();
        return redirect('/permissions')->with('success', 'Permission deleted successfully.');
    }
}
