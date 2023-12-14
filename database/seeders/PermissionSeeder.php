<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = ['add_article', 'edit_article', 'delete_article'];
        foreach( $permissions as $permission ){
            Permission::create(['name'=> $permission]);
        }

    }
}
