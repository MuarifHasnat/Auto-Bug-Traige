<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // প্রাথমিক ডেভেলপার ডাটা (স্কিল ট্যাগ ও প্রাথমিক টাস্ক সংখ্যা)
        DB::table('developers')->truncate();
        DB::table('developers')->insert([
            ['name' => 'Alice Rahman', 'skills' => 'ui css react tailwind frontend layout responsive navbar', 'active_tasks' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Bob Hasan', 'skills' => 'api database sql backend auth server node php timeout', 'active_tasks' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Charlie Tanvir', 'skills' => 'performance memory network bug logic testing fullstack crash', 'active_tasks' => 0, 'created_at' => now(), 'updated_at' => now()]
        ]);
    }
}