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
        Schema::create('mobile_apps', function (Blueprint $table) {
            $table->id();
            $table->string('app_name');
            $table->text('description');
            $table->string('icon_url')->nullable();
            $table->string('apk_download_url');
            $table->string('version')->default('1.0.0');
            $table->decimal('rating', 3, 1)->default(0);
            $table->string('review_count')->default('0');
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mobile_apps');
    }
};
