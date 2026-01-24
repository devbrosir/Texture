<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\ProcessRequest;
use Illuminate\Database\Seeder;

final class ProcessRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ProcessRequest::factory(20)->create();
    }
}
