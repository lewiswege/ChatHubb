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
        Schema::create('conversations', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('customer_id')->constrained()->cascadeOnDelete();
            $table->string('status')->default('new'); // ConversationStatus enum
            $table->foreignId('assigned_agent_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('last_message_at')->nullable();
            $table->string('last_inbound_channel')->nullable(); // ChannelType enum
            $table->unsignedInteger('unread_count')->default(0);
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            $table->index('customer_id');
            $table->index('assigned_agent_id');
            $table->index('status');
            $table->index('last_message_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
