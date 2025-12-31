<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Promotion;
use App\Models\Product;
use App\Models\Category;

class PromotionSeeder extends Seeder
{
    public function run(): void
    {
        // Product-based promotion: 30% off iPhone 15 Pro Max
        $iphone = Product::where('slug', 'iphone-15-pro-max')->first();
        if ($iphone) {
            $promo1 = Promotion::create([
                'name' => 'iPhone Flash Sale',
                'slug' => 'iphone-flash-sale',
                'description' => 'Get the latest iPhone at an incredible price! Limited time offer.',
                'discount_percentage' => 30.00,
                'start_date' => now()->subDays(1),
                'end_date' => now()->addDays(7),
                'applies_to' => 'product',
                'is_active' => true,
            ]);
            $promo1->products()->attach($iphone->id);
        }

        // Category-based promotion: 20% off all Mobile Phones
        $mobileCategory = Category::where('slug', 'mobile-phones')->first();
        if ($mobileCategory) {
            $promo2 = Promotion::create([
                'name' => 'Mobile Madness Sale',
                'slug' => 'mobile-madness-sale',
                'description' => 'Save big on all mobile phones! Upgrade your device today.',
                'discount_percentage' => 20.00,
                'start_date' => now()->subDays(2),
                'end_date' => now()->addDays(14),
                'applies_to' => 'category',
                'is_active' => true,
            ]);
            $promo2->categories()->attach($mobileCategory->id);
        }

        // Category-based promotion: 15% off Accessories
        $accessoriesCategory = Category::where('slug', 'accessories')->first();
        if ($accessoriesCategory) {
            $promo3 = Promotion::create([
                'name' => 'Accessory Bonanza',
                'slug' => 'accessory-bonanza',
                'description' => 'Stock up on essential accessories at discounted prices!',
                'discount_percentage' => 15.00,
                'start_date' => now(),
                'end_date' => now()->addDays(10),
                'applies_to' => 'category',
                'is_active' => true,
            ]);
            $promo3->categories()->attach($accessoriesCategory->id);
        }
    }
}
