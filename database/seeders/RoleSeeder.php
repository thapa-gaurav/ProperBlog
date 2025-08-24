<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
//        $adminRole = Role::create(['name'=>'admin']);
//
//        $manageRoles = PermissionController::create(['name'=>'manage role']);
//        $createPermission = PermissionController::create(['name'=>'manage permission']);
//        $deletePermission = PermissionController::create(['name'=>'delete articles']);
//        $allUserPermission = PermissionController::create(['name'=>'show users']);
//        $flagPassChange = PermissionController::create(['name'=> 'flag pass change']);
//
//        $adminRole->givePermissionTo($deletePermission,$allUserPermission,$flagPassChange,$manageRoles,$createPermission);

        $user = User::find(1);
        $user->assignRole('admin');

    }
}
