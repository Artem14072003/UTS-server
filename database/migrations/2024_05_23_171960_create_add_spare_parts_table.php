<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('add_spare_parts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('truck_id')->references('id')->on('spare_parts');
            $table->string('title');
            $table->string('value');
            $table->timestamps();
        });
    }

/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('add_spare_parts');
    }
};
