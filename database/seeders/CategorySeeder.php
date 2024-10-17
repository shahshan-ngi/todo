<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories=[
            ['name'=>'Work'],
            ['name'=>'Personal'],
            ['name'=>'Urgent'],
            ['name'=>'Home'],
            ['name'=>'Study']
        ];
        foreach($categories as $category){
            Category::create($category);
        }
    }
}
