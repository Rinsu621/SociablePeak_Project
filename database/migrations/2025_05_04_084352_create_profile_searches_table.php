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
        Schema::create('profile_searches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('searcher_id')->constrained('users')->onDelete('cascade'); // User who searched
            $table->foreignId('searched_id')->constrained('users')->onDelete('cascade'); // User whose profile was searched
            $table->timestamps();
            $table->unique(['searcher_id', 'searched_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile_searches');
    }
};
