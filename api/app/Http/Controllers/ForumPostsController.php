<?php
namespace App\Http\Controllers;
use App\Models\ForumPosts;
use App\Models\ForumCategory;
use App\Models\ForumTopics;
use Exception;
use Illuminate\Http\Request;

class ForumPostsController extends Controller {
    public function getPostsForTopic(Request $request){
        $this->validate($request, [
            'topic_id' => 'required'
        ]);

        try {
            $data = ForumPosts::where('topic_id', '=', $request['topic_id'])->get();
            return response()->json(array('success' => true, 'data' => $data), 200);
        } catch (Exception $e){
            return response()->json(array('success' => false, 'error' => $e), 200);
        }
    }
}