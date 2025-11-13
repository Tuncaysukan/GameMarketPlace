<?php

namespace Modules\Listing\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Listing\Entities\PromotionPackage;

class PromotionPackagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Boost Paketleri
        PromotionPackage::create([
            'name' => 'Boost 24 Saat',
            'type' => 'boost',
            'duration_days' => 1,
            'price' => 49.99,
            'description' => 'İlanınızı 24 saat boyunca üstte tutun',
            'position' => 1,
            'is_active' => true,
        ]);

        PromotionPackage::create([
            'name' => 'Boost 48 Saat',
            'type' => 'boost',
            'duration_days' => 2,
            'price' => 89.99,
            'description' => 'İlanınızı 48 saat boyunca üstte tutun',
            'position' => 2,
            'is_active' => true,
        ]);

        PromotionPackage::create([
            'name' => 'Boost 7 Gün',
            'type' => 'boost',
            'duration_days' => 7,
            'price' => 249.99,
            'description' => 'İlanınızı 7 gün boyunca üstte tutun',
            'position' => 3,
            'is_active' => true,
        ]);

        // Vitrin Paketleri
        PromotionPackage::create([
            'name' => 'Vitrin 7 Gün',
            'type' => 'featured',
            'duration_days' => 7,
            'price' => 299.99,
            'description' => 'İlanınız 7 gün boyunca ana sayfada vitrin alanında görünsün',
            'position' => 4,
            'is_active' => true,
        ]);

        PromotionPackage::create([
            'name' => 'Vitrin 15 Gün',
            'type' => 'featured',
            'duration_days' => 15,
            'price' => 499.99,
            'description' => 'İlanınız 15 gün boyunca ana sayfada vitrin alanında görünsün',
            'position' => 5,
            'is_active' => true,
        ]);

        PromotionPackage::create([
            'name' => 'Vitrin 30 Gün',
            'type' => 'featured',
            'duration_days' => 30,
            'price' => 899.99,
            'description' => 'İlanınız 30 gün boyunca ana sayfada vitrin alanında görünsün',
            'position' => 6,
            'is_active' => true,
        ]);
    }
}

