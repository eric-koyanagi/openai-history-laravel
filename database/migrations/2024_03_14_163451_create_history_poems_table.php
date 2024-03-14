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
        Schema::create('history_poems', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->text('poem');
            $table->unsignedBigInteger('run_id')->index();
            $table->foreign('run_id')->references('id')->on('data_runs');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_poems');
    }
};
