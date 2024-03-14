<?php

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
        Schema::create('history_libraries', function (Blueprint $table) {
            $table->id();
            $table->text('event');
            $table->text('image_prompt');
            $table->integer('month')->unsigned()->index();
            $table->integer('year')->unsigned()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_libraries');
    }
};
