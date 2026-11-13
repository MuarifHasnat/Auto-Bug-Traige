<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('project.{projectId}', function ($user, int $projectId) {
    return (int) \Illuminate\Support\Facades\DB::table('projects')->where('id', $projectId)->value('manager_id') === (int) $user->id
        || \Illuminate\Support\Facades\DB::table('project_members')->where('project_id', $projectId)->where('user_id', $user->id)->exists();
});
