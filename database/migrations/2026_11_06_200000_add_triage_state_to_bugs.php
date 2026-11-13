<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('bugs', function (Blueprint $table) {
            $table->string('triage_state', 20)->default('complete')->index();
        });
    }

    public function down(): void
    {
        Schema::table('bugs', fn (Blueprint $table) => $table->dropColumn('triage_state'));
    }
};
