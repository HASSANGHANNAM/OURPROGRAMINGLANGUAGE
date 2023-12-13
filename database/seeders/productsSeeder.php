<?php

namespace Database\Seeders;

use App\Models\product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class productsSeeder extends Seeder
{
    // مو جاهز
    public function run(): void
    {
        //$newImageName=uniqid().'-'. $request->title.'-'.$request->image->extension();
        //$request->image->move(public_path('image'),$newImageName);
        $products = [
            [
                'warehouse_id' => 1,
                'Price' => 1,
                'category_id' => 1,
                'made_by_id' => 1,
                'image' => "dsf",
                'marketing_name' => "sdf",
                'scientific_name' => "sdf",
                'arabic_name' => "شسشسي",
                'exp_date' => "2022-2-2"
            ], [
                'warehouse_id' => 1,
                'Price' => 1,
                'category_id' => 1,
                'made_by_id' => 1,
                'image' => "dsf",
                'marketing_name' => "sdf",
                'scientific_name' => "sdf",
                'arabic_name' => "شسشسي",
                'exp_date' => "2022-2-2"
            ],
            [
                'warehouse_id' => 1,
                'Price' => 1,
                'category_id' => 1,
                'made_by_id' => 1,
                'image' => "dsf",
                'marketing_name' => "sdf",
                'scientific_name' => "sdf",
                'arabic_name' => "شسشسي",
                'exp_date' => "2022-2-2"
            ]
        ];
        foreach ($products as $product) {
            $creatproduct = [
                'Price' => $product['Price'],
                'category_id' => $product['category_id'],
                'made_by_id' => $product['made_by_id'],
                'image' => $product['image'],
                'marketing_name' => $product['marketing_name'],
                'scientific_name' => $product['scientific_name'],
                'arabic_name' => $product['arabic_name'],
                'exp_date' => $product['exp_date']
            ];
            $p = product::create($creatproduct);
        }
    }
}
