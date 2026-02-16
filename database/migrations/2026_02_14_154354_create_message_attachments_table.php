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
        Schema::create('message_attachments', function (Blueprint $table) {
            $table->id();
            $table->ulid('message_id');
            $table->string('type'); // image, document, audio, video
            $table->string('file_path');
            $table->string('file_name');
            $table->unsignedBigInteger('file_size'); // in bytes
            $table->string('mime_type');
            $table->timestamps();

            $table->foreign('message_id')->references('id')->on('messages')->cascadeOnDelete();
            $table->index('message_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('message_attachments');
    }
};
