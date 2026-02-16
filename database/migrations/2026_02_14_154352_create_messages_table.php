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
        Schema::create('messages', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('conversation_id')->constrained()->cascadeOnDelete();
            $table->string('direction'); // MessageDirection enum
            $table->string('channel'); // ChannelType enum
            $table->string('channel_message_id')->nullable(); // External API message ID
            $table->ulid('reply_to_message_id')->nullable();
            $table->string('content_type'); // ContentType enum
            $table->text('content')->nullable();
            $table->json('metadata')->nullable(); // Channel-specific data
            $table->string('status')->default('pending'); // MessageStatus enum
            $table->timestamp('status_updated_at')->nullable();
            $table->text('failure_reason')->nullable();
            $table->foreignId('sent_by_agent_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->foreign('reply_to_message_id')->references('id')->on('messages')->nullOnDelete();

            $table->index(['conversation_id', 'created_at']);
            $table->index('channel_message_id');
            $table->index('status');
            $table->index('direction');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
