<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin'),
        ]);

        $role = Role::create(['name' => 'admin']);

        $permissions = Permission::pluck('id', 'id')->all();
        $role->syncPermissions($permissions);
        $user->assignRole([$role->id]);
    }
}
