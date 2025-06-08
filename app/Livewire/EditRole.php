<?php

namespace App\Livewire;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class EditRole extends Component
{
    public $roleId;
    public $name;
    public $selectedPermissions = [];
    public $permissions = [];

    public function mount($id)
    {
        $role = Role::find($id);

        if ($role) {
            $this->roleId = $role->id;
            $this->name = $role->name;
            $this->selectedPermissions = $role->permissions->pluck('id')->toArray();
            $this->permissions = Permission::all();
        }
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|unique:roles,name,' . $this->roleId,
        ]);
    
        $role = Role::find($this->roleId);
    
        if ($role) {
            $role->name = $this->name;
            $role->save();
    
         // Filter out non-numeric values from selectedPermissions
         $filteredPermissions = [];
         foreach ($this->selectedPermissions as $permissionId) {
             $permissionId = (int)$permissionId; // Cast to integer
             if ($permissionId > 0) {
                 $filteredPermissions[] = $permissionId;
             }
         }
 
         $role->syncPermissions($filteredPermissions);
         session()->flash('success', 'Role updated successfully');
         return redirect()->route('roles.index');
     }
 }
 

    public function render()
    {
        return view('livewire.edit-role');
    }
}
