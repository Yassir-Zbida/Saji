<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // General settings
            [
                'key' => 'site_name',
                'value' => 'Saji E-commerce',
                'group' => 'general',
            ],
            [
                'key' => 'site_description',
                'value' => 'Your online store for all your needs',
                'group' => 'general',
            ],
            [
                'key' => 'site_logo',
                'value' => 'logo.png',
                'group' => 'general',
            ],
            [
                'key' => 'site_favicon',
                'value' => 'favicon.ico',
                'group' => 'general',
            ],
            [
                'key' => 'site_email',
                'value' => 'contact@saji-ecommerce.com',
                'group' => 'general',
            ],
            [
                'key' => 'site_phone',
                'value' => '+1 234 567 890',
                'group' => 'general',
            ],
            [
                'key' => 'site_address',
                'value' => '123 Commerce Street, New York, NY 10001, USA',
                'group' => 'general',
            ],
            
            // Payment settings
            [
                'key' => 'currency',
                'value' => 'USD',
                'group' => 'payment',
            ],
            [
                'key' => 'currency_symbol',
                'value' => '$',
                'group' => 'payment',
            ],
            [
                'key' => 'tax_rate',
                'value' => '7',
                'group' => 'payment',
            ],
            [
                'key' => 'payment_methods',
                'value' => json_encode(['credit_card', 'paypal', 'bank_transfer']),
                'group' => 'payment',
            ],
            
            // Shipping settings
            [
                'key' => 'shipping_methods',
                'value' => json_encode(['standard', 'express', 'free']),
                'group' => 'shipping',
            ],
            [
                'key' => 'shipping_standard_cost',
                'value' => '5.99',
                'group' => 'shipping',
            ],
            [
                'key' => 'shipping_express_cost',
                'value' => '12.99',
                'group' => 'shipping',
            ],
            [
                'key' => 'shipping_free_minimum',
                'value' => '75',
                'group' => 'shipping',
            ],
            
            // Email settings
            [
                'key' => 'mail_driver',
                'value' => 'smtp',
                'group' => 'email',
            ],
            [
                'key' => 'mail_host',
                'value' => 'smtp.mailtrap.io',
                'group' => 'email',
            ],
            [
                'key' => 'mail_port',
                'value' => '2525',
                'group' => 'email',
            ],
            [
                'key' => 'mail_username',
                'value' => 'your_username',
                'group' => 'email',
            ],
            [
                'key' => 'mail_password',
                'value' => 'your_password',
                'group' => 'email',
            ],
            [
                'key' => 'mail_encryption',
                'value' => 'tls',
                'group' => 'email',
            ],
            [
                'key' => 'mail_from_address',
                'value' => 'noreply@saji-ecommerce.com',
                'group' => 'email',
            ],
            [
                'key' => 'mail_from_name',
                'value' => 'Saji E-commerce',
                'group' => 'email',
            ],
            
            // Social settings
            [
                'key' => 'social_facebook',
                'value' => 'https://facebook.com/saji-ecommerce',
                'group' => 'social',
            ],
            [
                'key' => 'social_twitter',
                'value' => 'https://twitter.com/saji-ecommerce',
                'group' => 'social',
            ],
            [
                'key' => 'social_instagram',
                'value' => 'https://instagram.com/saji-ecommerce',
                'group' => 'social',
            ],
            [
                'key' => 'social_linkedin',
                'value' => 'https://linkedin.com/company/saji-ecommerce',
                'group' => 'social',
            ],
        ];
        
        foreach ($settings as $settingData) {
            Setting::create($settingData);
        }
    }
}