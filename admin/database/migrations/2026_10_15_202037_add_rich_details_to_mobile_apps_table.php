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
        Schema::table('mobile_apps', function (Blueprint $table) {
            if (!Schema::hasColumn('mobile_apps', 'screenshots')) {
                $table->json('screenshots')->nullable()->after('icon_url');
            }
            if (!Schema::hasColumn('mobile_apps', 'whats_new')) {
                $table->text('whats_new')->nullable()->after('version');
            }
            if (!Schema::hasColumn('mobile_apps', 'file_size')) {
                $table->string('file_size')->nullable()->after('whats_new');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mobile_apps', function (Blueprint $table) {
            $columnsToDrop = [];
            if (Schema::hasColumn('mobile_apps', 'whats_new')) {
                $columnsToDrop[] = 'whats_new';
            }
            if (Schema::hasColumn('mobile_apps', 'file_size')) {
                $columnsToDrop[] = 'file_size';
            }
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
