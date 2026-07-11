<?php

declare(strict_types=1);

use App\Enums\TextureType;
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
        Schema::table('textures', function (Blueprint $table): void {
            $table->string('color', 128)->nullable();
            $table->tinyInteger('type')->default(TextureType::FLOOR->value);
            $table->json('tags')->nullable();
            $table->string('product_url', 2048)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('textures', function (Blueprint $table): void {
            $table->dropColumn('color');
            $table->dropColumn('type');
            $table->dropColumn('tags');
            $table->dropColumn('product_url');
        });
    }
};
