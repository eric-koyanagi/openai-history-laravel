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
        Schema::table('system_roles', function (Blueprint $table) {
            $table->string('model')->default('gpt-3.5-turbo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('system_roles', function (Blueprint $table) {
            $table->dropColumn('model');
        });
    }
};
