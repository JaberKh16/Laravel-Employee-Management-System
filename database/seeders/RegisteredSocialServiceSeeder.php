<?php

namespace Database\Seeders;

use App\Models\RegisteredSocialService;
use Illuminate\Database\Seeder;

class RegisteredSocialServiceSeeder extends Seeder
{
    public function run(): void
    {
        $map = [
            ['provider' => 'google', 'label' => 'Google', 'icon' => 'google', 'brand_color' => '#4285F4', 'sort_order' => 1],
            ['provider' => 'github', 'label' => 'GitHub', 'icon' => 'github', 'brand_color' => '#181717', 'sort_order' => 2],
            ['provider' => 'facebook', 'label' => 'Facebook', 'icon' => 'facebook', 'brand_color' => '#1877F2', 'sort_order' => 3],
            ['provider' => 'linkedin', 'label' => 'LinkedIn', 'icon' => 'linkedin', 'brand_color' => '#0A66C2', 'sort_order' => 4],
            ['provider' => 'twitter', 'label' => 'Twitter', 'icon' => 'twitter', 'brand_color' => '#1DA1F2', 'sort_order' => 5],
        ];

        foreach ($map as $row) {
            $cfg = config("services.{$row['provider']}");

            // Skip providers with no credentials configured
            if (empty($cfg['client_id']) || empty($cfg['client_secret'])) {
                continue;
            }

            RegisteredSocialService::updateOrCreate(
                ['provider' => $row['provider']],
                [
                    'label' => $row['label'],
                    'client_id' => $cfg['client_id'],
                    'client_secret' => $cfg['client_secret'],
                    'redirect_uri' => $cfg['redirect'] ?? url("/auth/{$row['provider']}/callback"),
                    'icon' => $row['icon'],
                    'brand_color' => $row['brand_color'],
                    'sort_order' => $row['sort_order'],
                    'is_enabled' => true,
                ]
            );
        }
    }
}