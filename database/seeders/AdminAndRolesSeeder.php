<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminAndRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('12345678'),
        ]);
        $roleAdmin= Role::create(['name' => 'admin']);
        $roleEditor= Role::create(['name' => 'editor']);
        $token = $admin->createToken('authToken'.$admin->name)->plainTextToken;
        $permissions = Permission::pluck('id','id')->all();
        $roleAdmin->syncPermissions($permissions);
        $admin->assignRole([$roleAdmin->id]);
        $this->command->info("Admin token: $token");
    }
}
