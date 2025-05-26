<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Package::create(
            [
                'package_name' => "Good@",
                'number_of_services' => 1,
                'number_of_images_per_service' => 2,
                'search_package_priority' => "High",
                'tasks_notification_criteria' => false,
                'has_price' => false,
                'is_public' => true,
                'per_month' => true,
                'slug' => "FREE",
                'description' => "Up to 1 service / Task|Appear on Search|2 Photos per service",
                'image' => "assets/img/subscriptions/avatar.svg",
                // 'color' => "#17b389",
                'color' => "linear-gradient(52deg, #17B391 0%, #18B277 100%)",
            ]
        );
        Package::create(
            [
                'package_name' => "Good@Friend",
                'number_of_services' => 3,
                'number_of_images_per_service' => 3,
                'search_package_priority' => "Normal",
                'tasks_notification_criteria' => true,
                'has_price' => false,
                'is_public' => true,
                'per_month' => true,
                'slug' => "Invite 2 Providers",
                'description' => "Up to 3 services / Tasks|Appear on Search|3 Photos per service",
                'image' => "assets/img/subscriptions/avatar.svg",
                // 'color' => "#1baae3",
                'color' => "linear-gradient(220deg, #1BA7E3 0%, #1CE3DC 100%)",
            ]
        );
        Package::create(
            [
                'package_name' => "Good@Ninja",
                'number_of_services' => null,
                'number_of_images_per_service' => 5,
                'search_package_priority' => "High",
                'tasks_notification_criteria' => true,
                'has_price' => true,
                'is_public' => true,
                'per_month' => true,
                'price' => 10,
                'slug' => "10/Year",
                'description' => "Unlimited Services / Tasks|Appear on Search top of Search|5 Photos per service",
                'image' => "assets/img/subscriptions/ninja.svg",
                // 'color' => "#f05549",
                'color' => "linear-gradient(220deg, #F0D448 0%, #F04949 100%)",
            ]
        );
        Package::create(
            [
                'package_name' => "Good@Master",
                'number_of_services' => 100000000,
                'number_of_images_per_service' => 5,
                'search_package_priority' => "High",
                'tasks_notification_criteria' => true,
                'has_price' => true,
                'is_public' => true,
                'per_month' => true,
                'price' => 10,
                'slug' => "50/Year",
                'description' => "Unlimited Services / Tasks|Appear on Search top of Search|5 Photos per service|Administrative supporting|Get notified for the new tasks",
                'image' => "assets/img/subscriptions/ninja.svg",
                // 'color' => "#f04d49",
                'color' => "linear-gradient(220deg, #C13BFF 0%, #1B5FAA 100%)",
            ]
        );
    }
}
