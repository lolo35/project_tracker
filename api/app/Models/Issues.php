<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Issues extends Model {
    protected $table = "issues";
    protected $fillable = ['project_id', 'status', 'issue', 'description', 'opened_by', 'closed_by'];
    protected $hidden = [];
}