<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sms_broadcasts', function (Blueprint $table) {
            $table->id();
            $table->text('message');
            $table->string('recipient_type'); // 'all' or 'zone'
            $table->foreignId('zone_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedInteger('recipient_count')->default(0);
            $table->string('bulk_message_id')->nullable();
            $table->string('status')->default('pending'); // pending, sent, failed
            $table->foreignId('sent_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sms_broadcasts');
    }
};
