<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\category;

class categorySeeder extends Seeder
{
    public function run(): void
    {
        $categorys = [
            ['Category_name' => "Painkillers", 'Arabic_Category_name' => "مسكنات"],
            ['Category_name' => "Antibiotics", 'Arabic_Category_name' => "مضادات حيوية"],
            ['Category_name' => "Heart medications", 'Arabic_Category_name' => "أدوية قلبية"],
            ['Category_name' => "Diabetes medications", 'Arabic_Category_name' => "أدوية السكري"],
            ['Category_name' => "Muscle relaxant", 'Arabic_Category_name' => "مرخي عضلي"],
            ['Category_name' => "Tranquilizers", 'Arabic_Category_name' => "مهدئات"],
            ['Category_name' => "Antidepressants", 'Arabic_Category_name' => "مضادات اكتئاب"]
        ];
        foreach ($categorys as $category) {
            category::create($category);
        }
    }
}
