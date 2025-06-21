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
        $permissions = [
            ['en' => 'show support messages', 'ar' => 'إظهار رسائل الدعم'],


        ];

        foreach ($permissions as $permission) {
            Permission::create([
                'name' => $permission['en'],
                'name_ar' => $permission['ar']
            ]);
        }
    }
}
