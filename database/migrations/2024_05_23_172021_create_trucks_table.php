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
        Schema::create('trucks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('desc');
            $table->float('price');
            $table->string('model');
            $table->string('year_release');
            $table->string('wheel_formula');
            $table->string('engine_power');
            $table->string('transmission');
            $table->string('fuel');
            $table->string('weight');
            $table->string('load_capacity');
            $table->string('engine_model');
            $table->string('wheels');
            $table->string('guarantee');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trucks');
    }
};
