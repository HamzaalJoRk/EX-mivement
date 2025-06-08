<?php

namespace App\Livewire;

use Livewire\Component;
use Spatie\Permission\Models\Permission;
class CreatePermission extends Component
{
    public $name;

    public function save()
    {
        $this->validate([
            'name' => 'required|unique:permissions,name',
        ]);

        Permission::create(['name' => $this->name]);

        session()->flash('success', 'Permission created successfully.');
        return redirect()->route('permissions.index');
    }

    public function render()
    {
        return view('livewire.create-permission');
    }
}
