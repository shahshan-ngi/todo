<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // DB::table('tasks')->insert(
        //     [
        //         'title'=>'seeder',
        //         'description'=>'insert from seeder',
        //         'duedate'=>'2024-10-11',
        //         'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
        //         'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        //     ]
        //     );

        // Task::insert([
        //     [
        //         'title' => 'data 1',
        //         'description' => 'insert from seeder',
        //         'duedate' => '2024-10-11',
           
        //     ],
        //     [
        //         'title' => 'data 2',
        //         'description' => 'insert from seeder',
        //         'duedate' => '2024-10-11',
        //     ]
        // ]);

        $tasks = [
            [
                'title' => 'data 3',
                'description' => 'insert from seeder',
                'duedate' => '2024-10-11',
            ],
            [
                'title' => 'data 4',
                'description' => 'insert from seeder',
                'duedate' => '2024-10-11',
            ]
        ];

        foreach ($tasks as $task) {
            Task::create($task);
        }
    }
}
