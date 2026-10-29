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
        if (!Schema::hasTable('beta_feedback')) {
            Schema::create('beta_feedback', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('mobile_app_id')->nullable();
                $table->string('package_name')->index();
                $table->string('app_name')->nullable();
                $table->string('app_version')->nullable();
                $table->string('type')->default('bug'); // bug, feedback, feature_request, commercial
                $table->text('message');
                $table->unsignedTinyInteger('rating')->nullable();
                $table->string('device_model')->nullable();
                $table->string('os_version')->nullable();
                $table->string('tester_name')->nullable();
                $table->string('tester_email')->nullable();
                $table->string('would_pay')->nullable(); // yes, no, maybe
                $table->string('status')->default('new'); // new, investigating, resolved, closed
                $table->text('admin_notes')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beta_feedback');
    }
};
