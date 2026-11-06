<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('bugs', function (Blueprint $table) {
            $table->foreignId('reporter_id')->nullable()->after('project_id')->constrained('users')->nullOnDelete();
        });

        Schema::create('bug_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('bug_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('author_name');
            $table->text('body');
            $table->timestamps();
            $table->index(['project_id', 'bug_id', 'created_at']);
        });

        Schema::create('bug_comment_mentions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('comment_id')->constrained('bug_comments')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unique(['comment_id', 'user_id']);
        });

        Schema::create('bug_watchers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bug_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamp('created_at')->useCurrent();
            $table->unique(['bug_id', 'user_id']);
        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            $table->index(['notifiable_type', 'notifiable_id', 'read_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('bug_watchers');
        Schema::dropIfExists('bug_comment_mentions');
        Schema::dropIfExists('bug_comments');
        Schema::table('bugs', function (Blueprint $table) {
            $table->dropConstrainedForeignId('reporter_id');
        });
    }
};
