<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecurringTasksModel extends Model {
    protected $table = "recurring_tasks";
    protected $fillable = ['user_id', 'task', 'status', 'description', 'dispatch_id', 'recurring', 'when'];
    protected $hidden = [];
}
