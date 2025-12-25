<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title',
        'priority',
        'due_date',
        'is_completed',
        'todo_list_id'
    ];

    public function todoList()
    {
        return $this->belongsTo(TodoList::class);
    }
}
