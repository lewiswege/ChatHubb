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
        Schema::create('customer_channel_identifiers', function (Blueprint $table) {
            $table->id();
            $table->foreignUlid('customer_id')->constrained()->cascadeOnDelete();
            $table->string('channel'); // ChannelType enum
            $table->string('identifier'); // phone, username, handle, etc
            $table->timestamp('verified_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->unique(['channel', 'identifier']);
            $table->index('customer_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_channel_identifiers');
    }
};
