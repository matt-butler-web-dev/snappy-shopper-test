<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ShopType;

class ShopTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ShopType::create([
            'id' => 1,
            'name' => 'Takeaway'
        ]);

        ShopType::create([
            'id' => 2,
            'name' => 'Shop'
        ]);

        ShopType::create([
            'id' => 3,
            'name' => 'Restaurant'
        ]);
    }
}
