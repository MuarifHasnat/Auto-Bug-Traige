<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('project_workflow_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('name', 40);
            $table->unsignedSmallInteger('position')->default(0);
            $table->boolean('is_done')->default(false);
            $table->timestamps();
            $table->unique(['project_id', 'name']);
            $table->index(['project_id', 'position']);
        });

        Schema::create('project_workflow_transitions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->string('from_status', 40);
            $table->string('to_status', 40);
            $table->timestamps();
            $table->unique(['project_id', 'from_status', 'to_status']);
        });

        $defaults = [
            ['To Do', false], ['In Progress', false], ['Review', false], ['Done', true], ['Duplicate', false],
        ];
        $defaultTransitions = [
            ['To Do', 'In Progress'], ['In Progress', 'To Do'], ['In Progress', 'Review'],
            ['Review', 'In Progress'], ['Review', 'Done'], ['Done', 'To Do'],
        ];

        foreach (DB::table('projects')->pluck('id') as $projectId) {
            foreach ($defaults as $position => [$name, $isDone]) {
                DB::table('project_workflow_statuses')->insert([
                    'project_id' => $projectId, 'name' => $name, 'position' => $position,
                    'is_done' => DB::raw($isDone ? 'true' : 'false'), 'created_at' => now(), 'updated_at' => now(),
                ]);
            }
            foreach ($defaultTransitions as [$from, $to]) {
                DB::table('project_workflow_transitions')->insert([
                    'project_id' => $projectId, 'from_status' => $from, 'to_status' => $to,
                    'created_at' => now(), 'updated_at' => now(),
                ]);
            }
        }

        DB::table('bugs')->where('status', 'Open')->update(['status' => 'To Do']);
        DB::table('bugs')->whereIn('status', ['Resolved', 'Closed'])->update(['status' => 'Done']);
        foreach (DB::table('saved_bug_filters')->get(['id', 'filters']) as $savedFilter) {
            $filters = json_decode($savedFilter->filters, true) ?: [];
            $filters['status'] = match ($filters['status'] ?? null) {
                'Open' => 'To Do', 'Resolved', 'Closed' => 'Done', default => $filters['status'] ?? null,
            };
            if (empty($filters['status'])) unset($filters['status']);
            DB::table('saved_bug_filters')->where('id', $savedFilter->id)->update(['filters' => json_encode($filters, JSON_THROW_ON_ERROR)]);
        }
    }

    public function down(): void
    {
        DB::table('bugs')->where('status', 'To Do')->update(['status' => 'Open']);
        DB::table('bugs')->where('status', 'Done')->update(['status' => 'Resolved']);
        Schema::dropIfExists('project_workflow_transitions');
        Schema::dropIfExists('project_workflow_statuses');
    }
};
