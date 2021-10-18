<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class RecurringTasksHistory extends Model {
    protected $table = "recurring_tasks_history";
    protected $fillable = ['dispatch_id', 'user_id', 'task_id', 'timeframe', 'minutesSpent', 'task'];
    protected $hidden = [];
}
