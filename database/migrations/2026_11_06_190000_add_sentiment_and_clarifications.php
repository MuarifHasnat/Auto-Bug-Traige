<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('bugs', function (Blueprint $table) {
            $table->string('sentiment', 20)->default('neutral');
            $table->smallInteger('sentiment_score')->default(0);
            $table->boolean('blocker_signal')->default(false);
        });

        Schema::create('bug_clarification_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('bug_id')->constrained()->cascadeOnDelete();
            $table->foreignId('requested_by')->constrained('users')->cascadeOnDelete();
            $table->foreignId('answered_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('question');
            $table->text('answer')->nullable();
            $table->string('status', 20)->default('open');
            $table->timestamp('answered_at')->nullable();
            $table->timestamps();
            $table->index(['project_id', 'bug_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bug_clarification_requests');
        Schema::table('bugs', function (Blueprint $table) {
            $table->dropColumn(['sentiment', 'sentiment_score', 'blocker_signal']);
        });
    }
};
