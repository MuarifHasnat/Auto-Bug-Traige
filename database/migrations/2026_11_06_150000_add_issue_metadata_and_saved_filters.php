<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('bugs', function (Blueprint $table) {
            $table->string('issue_type')->default('Bug')->after('status');
            $table->json('labels')->nullable()->after('issue_type');
        });

        Schema::create('saved_bug_filters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('name', 80);
            $table->json('filters');
            $table->timestamps();
            $table->unique(['user_id', 'project_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saved_bug_filters');
        Schema::table('bugs', function (Blueprint $table) {
            $table->dropColumn(['issue_type', 'labels']);
        });
    }
};
