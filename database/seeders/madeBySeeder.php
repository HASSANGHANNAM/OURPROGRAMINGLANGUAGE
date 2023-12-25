<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\made_by;

class madeBySeeder extends Seeder
{
    public function run(): void
    {
        $made_by = [
            ['made_by_name' => "IBN SINA", 'made_by_Arabic_name' => "ابن سينا"],
            ['made_by_name' => "ADAMCO", 'made_by_Arabic_name' => "أداما"],
            ['made_by_name' => "TAMECO", 'made_by_Arabic_name' => "تاميكو"],
            ['made_by_name' => "ARAK FARMA", 'made_by_Arabic_name' => "آراك فارما"],
            ['made_by_name' => "UNIPHARMA", 'made_by_Arabic_name' => "يوني فارما"]
        ];
        foreach ($made_by as $made) {
            made_by::create($made);
        }
    }
}
