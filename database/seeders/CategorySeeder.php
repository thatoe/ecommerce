<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $electronics = Category::create(['name' => 'Electronics']);
        $phones = Category::create(['name' => 'Phones', 'parent_id' => $electronics->id]);
        $laptops = Category::create(['name' => 'Laptops', 'parent_id' => $electronics->id]);

        $fashion = Category::create(['name' => 'Fashion']);
        Category::create(['name' => 'Men', 'parent_id' => $fashion->id]);
        Category::create(['name' => 'Women', 'parent_id' => $fashion->id]);
    }
}
