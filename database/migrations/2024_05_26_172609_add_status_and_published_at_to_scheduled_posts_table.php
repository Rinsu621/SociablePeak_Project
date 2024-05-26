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
        Schema::table('scheduled_posts', function (Blueprint $table) {
            Schema::table('scheduled_posts', function (Blueprint $table) {
                $table->integer('status')->after('set_time');
                $table->timestamp('published_at')->nullable()->after('status');
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scheduled_posts', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->dropColumn('published_at');
        });
    }
};
