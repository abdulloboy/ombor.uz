<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Material;
use App\Models\Product;
use App\Models\Warehouse;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $koylak=Product::create([
            'name' => "Ko'ylak" ]);
        $shim=Product::create([
            'name' => "Shim" ]);

        $mato=Material::create([
            'name' => "Mato" ]);
        $ip=Material::create([
            'name' => "Ip" ]);
        $tugma=Material::create([
            'name' => "Tugma" ]);
        $zamok=Material::create([
            'name' => "Zamok" ]);


        $koylak->materials()->attach($mato->id,
            ['quantity' => 0.8]);
        $koylak->materials()->attach($tugma->id,
            ['quantity' => 5]);
        $koylak->materials()->attach($ip->id,
            ['quantity' => 10]);
        
        $shim->materials()->attach($mato->id,
            ['quantity' => 1.4]);
        $shim->materials()->attach($ip->id,
            ['quantity' => 15]);
        $shim->materials()->attach($zamok->id,
            ['quantity' => 1]);

        Warehouse::create([
            'id' => '1',
            'material_id' => $mato->id,
            'remainder' => 12,
            'price' => 1500,
        ]);

        Warehouse::create([
            'id' => '2',
            'material_id' => $mato->id,
            'remainder' => 200,
            'price' => 1600,
        ]);

        Warehouse::create([
            'id' => '3',
            'material_id' => $ip->id,
            'remainder' => 40,
            'price' => 500,
        ]);

        Warehouse::create([
            'id' => '4',
            'material_id' => $ip->id,
            'remainder' => 300,
            'price' => 550,
        ]);

        Warehouse::create([
            'id' => '5',
            'material_id' => $tugma->id,
            'remainder' => 500,
            'price' => 300,
        ]);

        Warehouse::create([
            'id' => '6',
            'material_id' => $zamok->id,
            'remainder' => 1000,
            'price' => 2000,
        ]);
        
    }
}
