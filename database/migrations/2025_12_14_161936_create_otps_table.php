<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    public function up(): void
    {
        Schema::create('otps', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('identifier_field', 50);   // email|mobile
            $table->string('identifier_value', 191);
            $table->string('code', 20);
            $table->unsignedSmallInteger('retries')->default(0);
            $table->timestamp('expires_at');
            $table->ipAddress('ip')->nullable();
            $table->longText('extra')->nullable();
            $table->timestamps();

            $table->index(['identifier_field', 'identifier_value']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('otps');
    }
};
