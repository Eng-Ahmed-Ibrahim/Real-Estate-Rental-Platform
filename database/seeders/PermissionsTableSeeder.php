<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /** php artisan db:seed PermissionsTableSeeder

     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions=[

            ['name' => "show_reviews",
            "name_ar"=>"عرض المراجعات",
            'section' => "property",
            "guard_name"=>"web",
            ],
            ['name' => "delete_review",
            "name_ar"=>"حذف المراجعة",
            'section' => "property",
            "guard_name"=>"web",
            ],



        ];
        foreach($permissions as $permission){
            Permission::create($permission);
        }
    }
}
