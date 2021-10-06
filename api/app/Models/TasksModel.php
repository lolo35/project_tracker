<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class TasksModel extends Model {
    protected $table = "tasks";
    protected $fillable = ['userId', 'task', 'status', 'due', 'dispatch_id', 'minutesSpent'];
    protected $hidden = [];
}