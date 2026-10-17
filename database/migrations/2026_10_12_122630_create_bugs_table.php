<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bugs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('priority')->default('Medium'); // Low, Medium, High
            $table->string('status')->default('Open');     // Open, In Progress, In Review, Resolved, Closed
            $table->string('assigned_to')->nullable();
            $table->boolean('is_duplicate')->default(false);
            $table->unsignedBigInteger('duplicate_of')->nullable();
            $table->string('screenshot_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bugs');
    }
};