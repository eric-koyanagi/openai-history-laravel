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
        Schema::create('system_roles', function (Blueprint $table) {
            $table->id();
            $table->text('role');
            $table->float('temperature')->default(1.0);
            $table->float('frequency_penalty')->default(0);
            $table->float('presence_penalty')->default(0);
            $table->float('top_p')->default(1.0);
            $table->integer('max_tokens')->default(256);
            $table->timestamps();
        });

        Schema::create('data_runs', function (Blueprint $table) {
            $table->id();
            $table->integer('start_year')->unsigned()->index();
            $table->integer('end_year')->unsigned()->index();            
            $table->integer('current_month')->unsigned()->default(1)->index();
            $table->integer('current_year')->unsigned()->index();
            $table->unsignedBigInteger('system_role_id')->index();
            $table->foreign('system_role_id')->references('id')->on('system_roles');
            $table->boolean('done')->default(false);
            $table->timestamps();
        });

        Schema::create('histories', function (Blueprint $table) {
            $table->id();
            $table->integer('month')->unsigned()->index();
            $table->integer('year')->unsigned()->index();
            $table->text('event_1')->nullable();
            $table->text('event_2')->nullable();
            $table->text('event_3')->nullable();            
            $table->jsonb('raw_output')->nullable();
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
        Schema::dropIfExists('histories');
        Schema::dropIfExists('data_runs');
        Schema::dropIfExists('system_roles');
    }
};
