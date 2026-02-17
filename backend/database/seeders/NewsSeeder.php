<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\News;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        News::insert([
            [
                'date' => '2026-02-12',
                'title' => 'Revolutionary AI Technology Transforms Healthcare Industry',
                'category' => 'technology',
                'url' => 'https://example.com/ai-healthcare',
                'image_url' => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=800&q=80',
                'status' => 'published',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'date' => '2026-02-11',
                'title' => 'Global Markets Surge as Tech Giants Report Record Earnings',
                'category' => 'business',
                'url' => 'https://example.com/markets-surge',
                'image_url' => 'https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=800&q=80',
                'status' => 'published',
                'created_at' => now()->subDay(),
                'updated_at' => now()->subDay(),
            ],
            [
                'date' => '2026-02-10',
                'title' => 'Scientists Discover Breakthrough in Renewable Energy Storage',
                'category' => 'science',
                'url' => 'https://example.com/renewable-energy',
                'image_url' => 'https://images.unsplash.com/photo-1473341304170-971dccb5ac1e?w=800&q=80',
                'status' => 'unpublished',
                'created_at' => now()->subDays(2),
                'updated_at' => now()->subDays(2),
            ],
        ]);
    }
}
