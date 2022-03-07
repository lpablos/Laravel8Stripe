<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        
        $products = [            
            ['name'=>'MEDICA VRIM10', 'price'=>'10'],
            ['name'=>'MEDICA VRIM50', 'price'=>'50'],
            ['name'=>'MEDICA VRIM100', 'price'=>'100'],
            
        ];
        \DB::table('products')->insert($products);
    }
}
