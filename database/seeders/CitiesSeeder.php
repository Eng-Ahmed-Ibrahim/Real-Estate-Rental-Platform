<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = [
            // المحافظات
            ['name_en' => 'Cairo', 'name_ar' => 'القاهرة'],
            ['name_en' => 'Alexandria', 'name_ar' => 'الإسكندرية'],
            ['name_en' => 'Giza', 'name_ar' => 'الجيزة'],
            ['name_en' => 'Beheira', 'name_ar' => 'البحيرة'],
            ['name_en' => 'Kafr El Sheikh', 'name_ar' => 'كفر الشيخ'],
            ['name_en' => 'Dakahlia', 'name_ar' => 'الدقهلية'],
            ['name_en' => 'Sharqia', 'name_ar' => 'الشرقية'],
            ['name_en' => 'Qalyubia', 'name_ar' => 'القليوبية'],
            ['name_en' => 'Menofia', 'name_ar' => 'المنوفية'],
            ['name_en' => 'Gharbia', 'name_ar' => 'الغربية'],
            ['name_en' => 'Damietta', 'name_ar' => 'دمياط'],
            ['name_en' => 'Port Said', 'name_ar' => 'بورسعيد'],
            ['name_en' => 'Ismailia', 'name_ar' => 'الإسماعيلية'],
            ['name_en' => 'Suez', 'name_ar' => 'السويس'],
            ['name_en' => 'North Sinai', 'name_ar' => 'شمال سيناء'],
            ['name_en' => 'South Sinai', 'name_ar' => 'جنوب سيناء'],
            ['name_en' => 'Fayoum', 'name_ar' => 'الفيوم'],
            ['name_en' => 'Bani Suef', 'name_ar' => 'بني سويف'],
            ['name_en' => 'Minya', 'name_ar' => 'المنيا'],
            ['name_en' => 'Asyut', 'name_ar' => 'أسيوط'],
            ['name_en' => 'Sohag', 'name_ar' => 'سوهاج'],
            ['name_en' => 'Qena', 'name_ar' => 'قنا'],
            ['name_en' => 'Luxor', 'name_ar' => 'الأقصر'],
            ['name_en' => 'Aswan', 'name_ar' => 'أسوان'],
            ['name_en' => 'Red Sea', 'name_ar' => 'البحر الأحمر'],
            ['name_en' => 'New Valley', 'name_ar' => 'الوادي الجديد'],
            ['name_en' => 'Matruh', 'name_ar' => 'مطروح'],

            // مناطق الساحل الشمالي
            ['name_en' => 'Marassi', 'name_ar' => 'مراسي'],
            ['name_en' => 'Hacienda Bay', 'name_ar' => 'هاسيندا باي'],
            ['name_en' => 'Hacienda White', 'name_ar' => 'هاسيندا وايت'],
            ['name_en' => 'Diplo', 'name_ar' => 'ديبلو'],
            ['name_en' => 'Amwaj', 'name_ar' => 'أمواج'],
            ['name_en' => 'Sidi Abdelrahman', 'name_ar' => 'سيدي عبدالرحمن'],
            ['name_en' => 'Marina', 'name_ar' => 'مارينا'],
            ['name_en' => 'Fouka Bay', 'name_ar' => 'فوكا باي'],
            ['name_en' => 'Mountain View', 'name_ar' => 'ماونتن فيو'],
            ['name_en' => 'Bo Island', 'name_ar' => 'بو آيلاند'],
            ['name_en' => 'Bo Sands', 'name_ar' => 'بو ساندز'],

            // مناطق التجمع
            ['name_en' => 'First Settlement', 'name_ar' => 'التجمع الأول'],
            ['name_en' => 'Third Settlement', 'name_ar' => 'التجمع الثالث'],
            ['name_en' => 'Fifth Settlement', 'name_ar' => 'التجمع الخامس'],
            ['name_en' => 'Al Rehab', 'name_ar' => 'الرحاب'],
            ['name_en' => 'Madinaty', 'name_ar' => 'مدينتي'],
        ];

        DB::table('cities')->insert($locations);
    }
}
