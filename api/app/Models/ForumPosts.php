<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ForumPosts extends Model {
    protected $table = "forum_posts";
    protected $fillable = ['category_id', 'topic_id', 'name', 'description', 'type', 'views', 'replies', 'last_post'];
    protected $hidden = [];
}
