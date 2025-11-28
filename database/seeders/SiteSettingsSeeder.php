<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SiteSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = \Carbon\Carbon::now();
        SiteSetting::truncate();
        SiteSetting::insert([
            // Basic info
            ['setting_key' => 'title', 'setting_value' => 'My Awesome Site', 'created_at' => $now, 'updated_at' => $now],
            ['setting_key' => 'description', 'setting_value' => 'Best social service platform for automation.', 'created_at' => $now, 'updated_at' => $now],
            ['setting_key' => 'keywords', 'setting_value' => 'social, smm, automation, marketing', 'created_at' => $now, 'updated_at' => $now],

            // SEO / Media
            ['setting_key' => 'image_seo', 'setting_value' => '/assets/images/seo-default.png', 'created_at' => $now, 'updated_at' => $now],
            ['setting_key' => 'favicon', 'setting_value' => '/assets/images/favicon.ico', 'created_at' => $now, 'updated_at' => $now],
            ['setting_key' => 'logo', 'setting_value' => '/assets/images/logo.png', 'created_at' => $now, 'updated_at' => $now],

            // Site identity
            ['setting_key' => 'name', 'setting_value' => 'SubOld System', 'created_at' => $now, 'updated_at' => $now],
            ['setting_key' => 'admin_name', 'setting_value' => 'Nguyen Toan', 'created_at' => $now, 'updated_at' => $now],

            // Scripts
            ['setting_key' => 'script_header', 'setting_value' => null, 'created_at' => $now, 'updated_at' => $now],
            ['setting_key' => 'script_footer', 'setting_value' => null, 'created_at' => $now, 'updated_at' => $now],

            // Social links
            ['setting_key' => 'facebook', 'setting_value' => 'https://www.facebook.com', 'created_at' => $now, 'updated_at' => $now],
            ['setting_key' => 'telegram', 'setting_value' => 'https://t.me/example', 'created_at' => $now, 'updated_at' => $now],
            ['setting_key' => 'zalo', 'setting_value' => 'https://zalo.me/example', 'created_at' => $now, 'updated_at' => $now],

            // Policy and guides
            ['setting_key' => 'guide', 'setting_value' => '<p>Welcome to our user guide. Follow steps carefully.</p>', 'created_at' => $now, 'updated_at' => $now],
            ['setting_key' => 'policy', 'setting_value' => '<p>Your privacy is important to us.</p>', 'created_at' => $now, 'updated_at' => $now],
            ['setting_key' => 'terms', 'setting_value' => '<p>By using this site, you agree to our terms.</p>', 'created_at' => $now, 'updated_at' => $now],


            // System configs
            ['setting_key' => 'google_recaptcha', 'setting_value' => '0', 'created_at' => $now, 'updated_at' => $now],
            ['setting_key' => 'max_ip_attempts', 'setting_value' => '10', 'created_at' => $now, 'updated_at' => $now],
            ['setting_key' => 'max_user_login_attempts', 'setting_value' => '5', 'created_at' => $now, 'updated_at' => $now],
            ['setting_key' => 'max_user_error_attempts', 'setting_value' => '5', 'created_at' => $now, 'updated_at' => $now],
            ['setting_key' => 'order_allow', 'setting_value' => '0', 'created_at' => $now, 'updated_at' => $now],
            ['setting_key' => 'status', 'setting_value' => '1', 'created_at' => $now, 'updated_at' => $now],
            ['setting_key' => 'cloudflare_mode', 'setting_value' => '0', 'created_at' => $now, 'updated_at' => $now],

            // Promotion and levels
            ['setting_key' => 'bank_code', 'setting_value' => 'subold', 'created_at' => $now, 'updated_at' => $now],
            ['setting_key' => 'promotion_show', 'setting_value' => '1', 'created_at' => $now, 'updated_at' => $now],
            [
                'setting_key' => 'promotion_levels',
                'setting_value' => json_encode([
                    ['money' => 1000000, 'promotion' => 5],
                    ['money' => 5000000, 'promotion' => 10],
                    ['money' => 10000000, 'promotion' => 15],
                ], JSON_UNESCAPED_UNICODE),
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'setting_key' => 'user_levels',
                'setting_value' => json_encode([
                    '1' => ['discount' => 0, 'money' => 0],
                    '2' => ['discount' => 5, 'money' => 100000],
                    '3' => ['discount' => 10, 'money' => 500000],
                    '4' => ['discount' => 15, 'money' => 1000000],
                ], JSON_UNESCAPED_UNICODE),
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // Notices
            ['setting_key' => 'notice_modal', 'setting_value' => '<p>Welcome to SubOld — enjoy your experience!</p>', 'created_at' => $now, 'updated_at' => $now],
            ['setting_key' => 'notice_main', 'setting_value' => '<p>System maintenance scheduled for midnight.</p>', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
