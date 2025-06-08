<?php

namespace App\Livewire;

use Livewire\Component;
use Spatie\Permission\Models\Permission;

class EditPermission extends Component
{
    public $permissionId;
    public $name;

    public function mount(Permission $permission)
    {
        $this->permissionId = $permission->id;
        $this->name = $permission->name;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|unique:permissions,name,' . $this->permissionId,
        ]);

        $permission = Permission::find($this->permissionId);
        if ($permission) {
            $permission->name = $this->name;
            $permission->save();

            session()->flash('success', 'Permission updated successfully.');
            return redirect()->route('permissions.index');
        }
    }

    public function render()
    {
        return view('livewire.edit-permission');
    }
}
