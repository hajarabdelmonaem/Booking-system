<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('provider_availabilities', function (Blueprint $table) {
            $table->id();
            $table->string('day');
            $table->time('start_at');
            $table->time('end_at');
            $table->foreignId('provider_id')->constrained('providers')->cascadeOnDelete();
            $table->timestamps();
            $table->index(['day']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('provider_availabilities');
    }
};
