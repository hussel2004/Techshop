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
        $categories = [
            [
                'name' => 'Mobile Phones',
                'slug' => 'mobile-phones',
                'description' => 'Latest smartphones and mobile devices',
                'image' => null,
            ],
            [
                'name' => 'Computers',
                'slug' => 'computers',
                'description' => 'Laptops, desktops, and computer accessories',
                'image' => null,
            ],
            [
                'name' => 'Telephones',
                'slug' => 'telephones',
                'description' => 'Landline phones and cordless telephones',
                'image' => null,
            ],
            [
                'name' => 'Accessories',
                'slug' => 'accessories',
                'description' => 'Chargers, cases, headphones, and other accessories',
                'image' => null,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
