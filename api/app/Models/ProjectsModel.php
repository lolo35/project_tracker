<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ProjectsModel extends Model {
    protected $table = "projects";
    protected $fillable = ['teamId', 'name', 'description', 'priority'];
    protected $hidden = [];
}