<?php

namespace App\Http\Controllers;

use App\Models\BorderCrossing;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::all(); // or use pagination if there are many users
        return view('users.index', compact('users'));
    }

    public function editProfile()
    {
        $user = auth()->user();
        return view('users.edit_profile', compact('user'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed|min:6',
        ]);

        $user = auth()->user();
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->back()->with('success', 'تم تحديث كلمة المرور بنجاح.');
    }


    public function create()
    {
        if (!auth()->user()->can('create-users')) {
            abort(403, 'Unauthorized');
        }
        $allRoles = Role::pluck('name', 'name')->all();
        $borderCrossings = BorderCrossing::all();
        return view('users.create', [
            'allRoles' => $allRoles,
            'borderCrossings' => $borderCrossings
        ]);
    }

    public function create_user()
    {
        $allRoles = Role::pluck('name', 'name')->all();
        $borderCrossings = BorderCrossing::all();
        return view('users.create', [
            'allRoles' => $allRoles,
            'borderCrossings' => $borderCrossings
        ]);
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'sometimes|nullable|confirmed',
        ]);

        $input = $request->all();
        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            // If the password field is empty, remove it from the input
            unset($input['password']);
        }

        $user = User::create($input);
        $user->assignRole($request->input('roles'));

        return redirect()->route('users.index')
            ->with('success', 'User created successfully');
    }

    public function show($id)
    {
        $user = User::find($id);
        return view('users.show', compact('user'));
    }

    public function edit($id)
    {
        // if (!auth()->user()->can('edit-users')) {
        //     abort(403, 'Unauthorized');
        // }
        $user = User::find($id);
        $allRoles = Role::pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name')->all();

        return view('users.edit', compact('user', 'allRoles', 'userRole'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|confirmed',

        ]);

        $input = $request->all();

        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            unset($input['password']);
            unset($input['password_confirmation']);
        }

        $user = User::find($id);
        $user->update($input);

        // Update roles based on the selected checkboxes
        if ($request->has('roles')) {
            $user->syncRoles($request->input('roles'));
        } else {
            $user->syncRoles([]); // Remove all roles if none are selected
        }

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully');
    }

    public function destroy(User $user)
    {
        if (!auth()->user()->can('delete-users')) {
            abort(403, 'Unauthorized');
        }
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }
}