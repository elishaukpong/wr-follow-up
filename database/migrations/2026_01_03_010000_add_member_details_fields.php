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
        Schema::table('members', function (Blueprint $table) {
            $table->enum('gender', ['male', 'female'])->nullable()->after('email');
            $table->text('notes')->nullable()->after('custom_location');
            $table->enum('follow_up_status', ['pending', 'contacted', 'connected', 'no_response'])->nullable()->after('notes');
            $table->timestamp('followed_up_at')->nullable()->after('follow_up_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn(['gender', 'notes', 'follow_up_status', 'followed_up_at']);
        });
    }
};
