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
        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->string('document_key'); // References the original document key
            $table->string('language', 10); // 'vi', 'en', etc.
            $table->string('title');
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->string('slug');
            $table->json('frontmatter')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamps();

            // Indexes for better performance
            $table->index(['document_key', 'language']);
            $table->index(['language', 'is_published']);
            $table->unique(['document_key', 'language']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('translations');
    }
};
