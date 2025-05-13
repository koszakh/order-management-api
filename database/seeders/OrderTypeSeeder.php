<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\OrderType;

class OrderTypeSeeder extends Seeder {
    public function run(): void {
        OrderType::query()->delete();

        OrderType::create(['name' => 'Loading/unloading']);
        OrderType::create(['name' => 'Rigging works']);
        OrderType::create(['name' => 'Cleaning']);
    }
}
