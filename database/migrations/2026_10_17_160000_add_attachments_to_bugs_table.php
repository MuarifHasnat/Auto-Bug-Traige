<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('bugs', function (Blueprint $table) {
            $table->string('source_file_path')->nullable()->after('screenshot_path');
            $table->string('source_file_name')->nullable()->after('source_file_path');
            $table->string('detected_feature')->nullable()->after('source_file_name');
        });
    }

    public function down(): void
    {
        Schema::table('bugs', function (Blueprint $table) {
            $table->dropColumn(['source_file_path', 'source_file_name', 'detected_feature']);
        });
    }
};
