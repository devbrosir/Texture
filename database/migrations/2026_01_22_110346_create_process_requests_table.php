<?php

declare(strict_types=1);

use App\Enums\RequestStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('process_requests', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id');
            $table->tinyInteger('status')->default(RequestStatus::PENDING->value);
            $table->timestamp('processed_at')->nullable();
            $table->longText('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('process_requests');
    }
};
