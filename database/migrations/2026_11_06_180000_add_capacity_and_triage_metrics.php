<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('developers', function (Blueprint $table) {
            $table->unsignedSmallInteger('capacity')->default(5)->after('active_tasks');
        });
        Schema::table('bugs', function (Blueprint $table) {
            $table->string('recommended_assignee')->nullable()->after('assigned_to');
        });
        Schema::create('bug_triage_metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('bug_id')->unique()->constrained()->cascadeOnDelete();
            $table->unsignedInteger('triage_duration_ms');
            $table->decimal('duplicate_similarity_score', 5, 1)->default(0);
            $table->boolean('predicted_duplicate')->default(false);
            $table->boolean('actual_duplicate')->nullable();
            $table->string('recommended_assignee')->nullable();
            $table->string('assignment_feedback')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
            $table->index(['project_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bug_triage_metrics');
        Schema::table('bugs', fn (Blueprint $table) => $table->dropColumn('recommended_assignee'));
        Schema::table('developers', fn (Blueprint $table) => $table->dropColumn('capacity'));
    }
};
