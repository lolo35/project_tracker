<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ProjectPriorityHistory extends Model {
    protected $table = "project_priority_history";
    protected $fillable = ['project_priority', 'project_id', 'comment'];
    protected $hidden = [];
}