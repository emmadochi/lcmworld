<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MobileApp;
use App\Models\Category;

class DummyAppsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dummyApps = [
            [
                'app_name' => 'Nexus Productivity',
                'package_name' => 'com.nexus.productivity',
                'description' => 'The ultimate enterprise productivity suite for managing your tasks, teams, and time.',
                'version' => '2.1.0',
                'icon_url' => 'https://picsum.photos/seed/nexus/200/200',
                'apk_download_url' => 'https://ash-speed.hetzner.com/100MB.bin',
            ],
            [
                'app_name' => 'DataSync Pro',
                'package_name' => 'com.datasync.pro',
                'description' => 'Securely sync your company data across all devices with military-grade encryption.',
                'version' => '1.0.5',
                'icon_url' => 'https://picsum.photos/seed/datasync/200/200',
                'apk_download_url' => 'https://ash-speed.hetzner.com/100MB.bin',
            ],
            [
                'app_name' => 'SalesForce Connect',
                'package_name' => 'com.enterprise.sales',
                'description' => 'On-the-go access to your sales CRM. Close deals faster and communicate with clients.',
                'version' => '3.4.1',
                'icon_url' => 'https://picsum.photos/seed/sales/200/200',
                'apk_download_url' => 'https://ash-speed.hetzner.com/100MB.bin',
            ],
            [
                'app_name' => 'TeamChat Secure',
                'package_name' => 'com.teamchat.secure',
                'description' => 'Internal communication tool for enterprises. Features end-to-end encryption.',
                'version' => '4.0.0',
                'icon_url' => 'https://picsum.photos/seed/chat/200/200',
                'apk_download_url' => 'https://ash-speed.hetzner.com/100MB.bin',
            ],
            [
                'app_name' => 'CloudVault',
                'package_name' => 'com.cloudvault.app',
                'description' => 'Store and share large files securely within your organization network.',
                'version' => '1.2.3',
                'icon_url' => 'https://picsum.photos/seed/cloud/200/200',
                'apk_download_url' => 'https://ash-speed.hetzner.com/100MB.bin',
            ],
            [
                'app_name' => 'HR Portal Mobile',
                'package_name' => 'com.company.hr',
                'description' => 'Manage your time off, benefits, and payroll information from your mobile device.',
                'version' => '1.1.0',
                'icon_url' => 'https://picsum.photos/seed/hr/200/200',
                'apk_download_url' => 'https://ash-speed.hetzner.com/100MB.bin',
            ],
            [
                'app_name' => 'Analytics Dashboard',
                'package_name' => 'com.analytics.dash',
                'description' => 'Real-time business intelligence and analytics right in your pocket.',
                'version' => '2.5.0',
                'icon_url' => 'https://picsum.photos/seed/analytics/200/200',
                'apk_download_url' => 'https://ash-speed.hetzner.com/100MB.bin',
            ]
        ];

        if (Category::count() === 0) {
            $categories = ['Productivity', 'Communication', 'Analytics', 'HR', 'Security'];
            foreach ($categories as $catName) {
                Category::create(['name' => $catName]);
            }
        }

        foreach ($dummyApps as $appData) {
            $cat = Category::inRandomOrder()->first();
            
            MobileApp::create([
                'app_name' => $appData['app_name'],
                'package_name' => $appData['package_name'],
                'description' => $appData['description'],
                'category_id' => $cat ? $cat->id : null,
                'version' => $appData['version'],
                'icon_url' => $appData['icon_url'],
                'apk_download_url' => $appData['apk_download_url'],
                'rating' => rand(35, 50) / 10, // 3.5 to 5.0
                'review_count' => rand(10, 500)
            ]);
        }
    }
}
