<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\made_by;

class madeBySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $made_by = ['sdda'];

        foreach ($made_by as $made_byName) {
            made_by::create(['made_by_name' => $made_byName]);
        }
    }
}
