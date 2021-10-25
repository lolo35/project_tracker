<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ForumPostsDetails extends Model {
    protected $table = "forum_posts_details";
    protected $fillable = ['topic_id', 'post_id', 'user_id', 'text'];
    protected $hidden = [];
}