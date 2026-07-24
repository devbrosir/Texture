<?php

declare(strict_types=1);

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
            $table->foreignId('type_id')->nullable()->constrained('texture_types');
            $table->foreignId('category_id')->nullable()->constrained('texture_categories');
            $table->dropColumn('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('textures', function (Blueprint $table): void {
            $table->dropForeign('textures_type_id_foreign');
            $table->dropForeign('textures_category_id_foreign');
            $table->dropColumn('type_id');
            $table->dropColumn('category_id');
            $table->tinyInteger('type')->default(1);
        });
    }
};
