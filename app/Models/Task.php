<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $table='tasks';
    protected $fillable=[
        'title',
        'description',
        'status',
        'duedate'
    ];

    public static function search($search)
    {
        return self::where('title', 'like', '%' . $search . '%')
            ->orWhere('description', 'like', '%' . $search . '%');
    }


    public static function createTask(array $data)
    {
        return self::create($data);
    }

 
    public static function updateTask($id, array $data)
    {
        $task = self::findOrFail($id);
        $task->update($data);
        return $task;
    }

 
    public static function deleteTask($id)
    {
        $task = self::findOrFail($id);
        $task->delete();
        return $task;
    }
}
