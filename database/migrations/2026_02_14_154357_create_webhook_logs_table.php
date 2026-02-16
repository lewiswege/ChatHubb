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
        Schema::create('webhook_logs', function (Blueprint $table) {
            $table->id();
            $table->string('channel'); // ChannelType enum
            $table->json('payload');
            $table->json('headers')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('status')->default('pending'); // pending, success, failed
            $table->timestamp('processed_at')->nullable();
            $table->ulid('message_id')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->foreign('message_id')->references('id')->on('messages')->nullOnDelete();
            $table->index('channel');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webhook_logs');
    }
};
