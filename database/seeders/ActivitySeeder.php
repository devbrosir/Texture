<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\ActivityType;
use App\Models\Activity;
use App\Models\Scene;
use App\Models\SceneCategory;
use App\Models\Texture;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;
use Modules\User\Models\User;

class ActivitySeeder extends Seeder
{
    public function run(): void
    {
        // Get or create test users
        $users = User::factory()->count(5)->create();

        // Get existing data or create if empty
        $scenes = Scene::all();
        if ($scenes->isEmpty()) {
            $scenes = Scene::factory()->count(10)->create();
        }

        $textures = Texture::all();
        if ($textures->isEmpty()) {
            $textures = Texture::factory()->count(15)->create();
        }

        $categories = SceneCategory::all();
        if ($categories->isEmpty()) {
            $categories = SceneCategory::factory()->count(5)->create();
        }

        // Define activity types with their weights (probability)
        $activityTypes = [
            ActivityType::ViewPage->value => 100,
            ActivityType::ViewBanner->value => 30,
            ActivityType::ClickDetail->value => 50,
            ActivityType::ClickProductLink->value => 20,
            ActivityType::ClickBanner->value => 15,
            ActivityType::ClickLogin->value => 10,
            ActivityType::ClickLoginSso->value => 5,
            ActivityType::SelectPart->value => 40,
            ActivityType::SelectScene->value => 60,
            ActivityType::SelectTexture->value => 55,
            ActivityType::SelectSceneCategory->value => 35,
            ActivityType::CompareActivate->value => 15,
            ActivityType::Download->value => 25,
            ActivityType::DownloadBlocked->value => 5,
            ActivityType::UploadSuccess->value => 8,
            ActivityType::UploadFailed->value => 3,
            ActivityType::LoginSuccess->value => 20,
            ActivityType::LoginFailed->value => 8,
            ActivityType::Logout->value => 12,
        ];

        // Generate activities for the last 30 days
        $startDate = Date::now()->subDays(30);
        $endDate = Date::now();

        $activities = [];
        $totalActivities = random_int(500, 1000);

        for ($i = 0; $i < $totalActivities; $i++) {
            // Random date within the last 30 days
            $createdAt = Date::createFromTimestamp(
                random_int($startDate->timestamp, $endDate->timestamp)
            );

            // Select activity type based on weights
            $typeValue = $this->getWeightedRandom($activityTypes);

            // Random user or guest
            $userId = random_int(1, 10) <= 4 ? null : $users->random()->id;

            // Determine related model based on type
            $related = null;
            $relatedType = null;

            if (in_array($typeValue, [
                ActivityType::SelectScene->value,
                ActivityType::ViewPage->value,
            ])) {
                $related = $scenes->random();
                $relatedType = Scene::class;
            } elseif (in_array($typeValue, [
                ActivityType::SelectTexture->value,
                ActivityType::ClickProductLink->value,
            ])) {
                $related = $textures->random();
                $relatedType = Texture::class;
            } elseif ($typeValue === ActivityType::SelectSceneCategory->value) {
                $related = $categories->random();
                $relatedType = SceneCategory::class;
            }

            $activities[] = [
                'user_id' => $userId,
                'uuid' => Str::uuid(),
                'type' => $typeValue, // Use the string value directly
                'related_id' => $related?->id,
                'related_type' => $relatedType,
                'metadata' => json_encode($this->generateMetadata($typeValue, $related)), // Encode metadata as JSON
                'created_at' => $createdAt,
            ];
        }

        // Insert activities in chunks
        foreach (array_chunk($activities, 100) as $chunk) {
            Activity::query()->insert($chunk);
        }

        $this->command->info('Created '.count($activities).' test activities!');
    }

    /**
     * Get weighted random item from array
     */
    private function getWeightedRandom(array $weights): string
    {
        $totalWeight = array_sum($weights);
        $random = random_int(1, $totalWeight);

        foreach ($weights as $item => $weight) {
            $random -= $weight;
            if ($random <= 0) {
                return $item;
            }
        }

        return array_key_first($weights);
    }

    /**
     * Generate realistic metadata for activities
     */
    private function generateMetadata(string $type, $related): array
    {
        $metadata = [];

        if ($type === ActivityType::SelectScene->value) {
            $metadata = [
                'scene_id' => $related?->id,
                'scene_title' => $related?->title ?? 'Unknown',
                'position' => random_int(1, 20),
                'device' => $this->randomDevice(),
            ];
        } elseif ($type === ActivityType::SelectTexture->value) {
            $metadata = [
                'texture_id' => $related?->id,
                'texture_title' => $related?->title ?? 'Unknown',
                'width' => $related?->width ?? random_int(100, 1000),
                'height' => $related?->height ?? random_int(100, 1000),
                'device' => $this->randomDevice(),
            ];
        } elseif ($type === ActivityType::ViewPage->value) {
            $metadata = [
                'page' => $this->randomPage(),
                'referrer' => $this->randomReferrer(),
                'device' => $this->randomDevice(),
            ];
        } elseif ($type === ActivityType::Download->value) {
            $metadata = [
                'file_size' => random_int(1024, 102400),
                'file_type' => $this->randomFileType(),
                'duration' => random_int(1, 30),
            ];
        } elseif ($type === ActivityType::LoginSuccess->value || $type === ActivityType::LoginFailed->value) {
            $metadata = [
                'ip' => $this->randomIP(),
                'device' => $this->randomDevice(),
                'browser' => $this->randomBrowser(),
            ];
        } elseif ($type === ActivityType::CompareActivate->value) {
            $metadata = [
                'compare_items' => random_int(2, 4),
                'session_id' => Str::random(32),
            ];
        }

        return $metadata;
    }

    private function randomDevice(): string
    {
        $devices = ['Desktop', 'Mobile', 'Tablet', 'Android', 'iOS', 'Windows'];

        return $devices[array_rand($devices)];
    }

    private function randomPage(): string
    {
        $pages = ['/home', '/products', '/gallery', '/editor', '/profile', '/settings', '/dashboard'];

        return $pages[array_rand($pages)];
    }

    private function randomReferrer(): string
    {
        $referrers = ['google.com', 'bing.com', 'yahoo.com', 'instagram.com', 'telegram.me', 'direct'];

        return $referrers[array_rand($referrers)];
    }

    private function randomFileType(): string
    {
        $types = ['jpg', 'png', 'svg', 'pdf', 'psd', 'zip'];

        return $types[array_rand($types)];
    }

    private function randomIP(): string
    {
        return random_int(1, 255).'.'.random_int(0, 255).'.'.random_int(0, 255).'.'.random_int(0, 255);
    }

    private function randomBrowser(): string
    {
        $browsers = ['Chrome', 'Firefox', 'Safari', 'Edge', 'Opera', 'Samsung Internet'];

        return $browsers[array_rand($browsers)];
    }
}
