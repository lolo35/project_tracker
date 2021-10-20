<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ForumTopics extends Model {
    protected $table = "forum_topics";
    protected $fillable = ['topic', 'category_id', 'description'];
    protected $hidden = [];
}