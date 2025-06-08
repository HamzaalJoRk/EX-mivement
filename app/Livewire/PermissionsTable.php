<?php

namespace App\Livewire;

use Livewire\Component;
use Spatie\Permission\Models\Permission;
class PermissionsTable extends Component
{
    public function render()
    {
        $permissions = Permission::all(); // Get all permissions
        return view('livewire.permissions-table', compact('permissions'));
    }
}
