<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table='tasks';
    protected $fillable=[
        'title',
        
        'status',
        'duedate'
    ];

    public function scopeSearch($query, $searchTerm)
    {
        return $query->where('title', 'like', '%' . $searchTerm . '%')
                     ->orWhere('description', 'like', '%' . $searchTerm . '%');
    }

    public function scopeActive($query){
        return $query->where('status',0);
    }
    public function scopeCompleted($query){
        return $query->where('status',1);
    }


    public static function createTask($data)
    {
        return self::create(['title'=>$data->title,'description'=>$data->description,'duedate'=>$data->duedate])->categories()->attach($data->categories);
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

    public function categories(){
        return $this->belongsToMany(Category::class);
    }
    public function getDuedateAttribute($value)
    {
        return Carbon::createFromFormat('Y-m-d', $value)->format('d M Y');
      
    }
}
