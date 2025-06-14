<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'اضافة مستخدم',
            'تعديل مستخدم',
            'حذف مستخدم',
            'عرض مستخدم',
            
            'اضافة صلاحية',
            'تعديل صلاحية',
            'حذف صلاحية',
            'عرض صلاحية',
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(['name' => $permission]);
        }

        $superAdminRole = Role::updateOrCreate(['name' => 'Admin']);
        $userRole = Role::updateOrCreate(['name' => 'Customs']);
        $CustomEntry = Role::updateOrCreate(['name' => 'CustomEntry']);
        $CustomExit = Role::updateOrCreate(['name' => 'CustomExit']);   
        $FinanceRole = Role::updateOrCreate(['name' => 'Finance']);
        $superAdminRole->givePermissionTo($permissions);

        $superAdmin = User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            ['name' => 'المدير العام', 'password' => Hash::make('password')]
        );

        $superAdmin->assignRole($superAdminRole);

        $user = User::updateOrCreate(
            ['email' => 'user@example.com'],
            ['name' => 'User', 'password' => Hash::make('password')]
        );

        $user->assignRole($userRole);
    }
}
