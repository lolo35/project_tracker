<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class StartedTasks extends Model {
    protected $table = "started_tasks";
    protected $fillable = ['dispatch_id', 'user_id', 'task_id', 'timeframe', 'minutesSpent'];
    protected $hidden = [];
}