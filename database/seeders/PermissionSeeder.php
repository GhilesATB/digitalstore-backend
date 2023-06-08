<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = collect([
            ['name' => 'view-store', 'description' => 'view store menu'],

            ['name' => 'view-products', 'description' => 'view products'],
            ['name' => 'create-products', 'description' => 'view products'],
            ['name' => 'edit-products', 'description' => 'edit products'],
            ['name' => 'delete-products', 'description' => 'delete products'],

            ['name' => 'view-category', 'description' => 'view category'],
            ['name' => 'create-category', 'description' => 'create category'],
            ['name' => 'edit-category', 'description' => 'edit category'],
            ['name' => 'delete-category', 'description' => 'edit category'],

            ['name' => 'view-accounts', 'description' => 'view store menu'],

            ['name' => 'view-role', 'description' => 'view role'],
            ['name' => 'create-role', 'description' => 'view role'],
            ['name' => 'edit-role', 'description' => 'edit role'],
            ['name' => 'delete-role', 'description' => 'delete role'],

            ['name' => 'view-permission', 'description' => 'category permission'],
            ['name' => 'assign-permission', 'description' => 'assign permission'],

        ]);

        $permissions->each(fn($permission) => Permission::create($permission));
    }
}
