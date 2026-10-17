<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('manager_id')->constrained('users')->cascadeOnDelete();
            $table->string('name');
            $table->string('stack')->nullable();
            $table->timestamps();
        });

        Schema::create('project_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unique(['project_id', 'user_id']);
        });

        Schema::table('bugs', function (Blueprint $table) {
            $table->foreignId('project_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
            $table->index(['project_id', 'status']);
        });

        Schema::table('developers', function (Blueprint $table) {
            $table->foreignId('project_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
            $table->index('project_id');
        });
    }

    public function down(): void
    {
        Schema::table('developers', fn (Blueprint $table) => $table->dropConstrainedForeignId('project_id'));
        Schema::table('bugs', fn (Blueprint $table) => $table->dropConstrainedForeignId('project_id'));
        Schema::dropIfExists('project_members');
        Schema::dropIfExists('projects');
    }
};
