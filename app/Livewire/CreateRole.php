<?php

namespace App\Livewire;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CreateRole extends Component
{
    public $name;
    public $selectedPermissions = [];
    public $permissions = [];

    public function mount()
    {
        $this->permissions = Permission::all();
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|unique:roles,name',
        ]);

        $role = Role::create(['name' => $this->name]);
         // Filter out non-numeric values from selectedPermissions
         $filteredPermissions = [];
         foreach ($this->selectedPermissions as $permissionId) {
             $permissionId = (int)$permissionId; // Cast to integer
             if ($permissionId > 0) {
                 $filteredPermissions[] = $permissionId;
             }
         }
 
         $role->syncPermissions($filteredPermissions);
 
         session()->flash('success', 'Role created successfully');
         return redirect()->route('roles.index');
     }
 
    public function render()
    {
        return view('livewire.create-role');
    }
}