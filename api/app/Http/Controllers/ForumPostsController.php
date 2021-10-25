<?php
namespace App\Http\Controllers;
use App\Models\ForumPosts;
use App\Models\ForumCategory;
use App\Models\ForumTopics;
use App\Models\ForumPostsDetails;
use Exception;
use ForumPostDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function createTopic(Request $request){
        $this->validate($request, [
            'category_id' => 'required',
            'topic_id' => 'required',
            'name' => 'required',
            'description' => 'required',
            'text' => 'required',
            'username' => 'required',
            'user_id' => 'required',
        ]);

        try {
            $insert = new ForumPosts();
            $insert->category_id = $request['category_id'];
            $insert->topic_id = $request['topic_id'];
            $insert->name = $request['name'];
            $insert->description = $request['description'];
            $insert->type = "post";
            $insert->views = 0;
            $insert->replies = 0;
            $insert->last_post = $request['username'];
            $insert->save();

            $insertDetails = new ForumPostsDetails();
            $insertDetails->topic_id = $request['topic_id'];
            $insertDetails->text = $request['text'];
            $insertDetails->user_id = $request['user_id'];
            $insertDetails->post_id = $insert['id'];
            $insertDetails->save();

            return response()->json(array('success' => true), 200);
        } catch (Exception $e) {
            return response()->json(array('success' => false, 'error' => $e), 200);
        }
    }

    public function getTopicDiscution(Request $request){
        $this->validate($request, [
            'topic_id' => 'required'
        ]);

        try {
            $postData = ForumPosts::where('id', '=', $request['topic_id'])->get();
            $postDiscution = DB::table('forum_posts_details')
                ->join('users', 'forum_posts_details.user_id', '=', 'users.id')
                ->select('forum_posts_details.*', 'users.name')
                ->where('post_id', '=', $request['topic_id'])
                ->orderBy('created_at', 'asc')
                ->get();
            return response()->json(array('success' => true, 'postData' => $postData, 'postDiscution' => $postDiscution), 200);

        } catch (Exception $e){
            return response()->json(array('success' => false, 'error' => $e), 200);
        }
    }

    public function postReply(Request $request){
        $this->validate($request, [
            'topic_id' => 'required',
            'post_id' => 'required',
            'text' => 'required',
            'user_id' => 'required'
        ]);

        try {
            // ForumPostsDetails::updateOrCreate(
            //     [
            //         'topic_id' => $request['topic_id'],
            //         'post_id' => $request['post_id'],
            //         'user_id' => $request['user_id'],
            //     ],
            //     [
            //         'text' => $request['text']
            //     ]
            // );
            $reply = new ForumPostsDetails();
            $reply->topic_id = $request['topic_id'];
            $reply->post_id = $request['post_id'];
            $reply->user_id = $request['user_id'];
            $reply->text = $request['text'];
            if($reply->save()){
                ForumPosts::where('id','=', $reply['post_id'])->increment('replies', 1);
                $data = DB::table('forum_posts_details')
                    ->join('users', 'forum_posts_details.user_id', '=', 'users.id')
                    ->select('forum_posts_details.*', 'users.name')
                    ->where('forum_posts_details.id', '=', $reply['id'])
                    ->get();
                return response()->json(array('success' => true, 'reply' => $data), 200);
            }else{
                return response()->json(array('success' => false), 200);
            }
        } catch (Exception $e){
            return response()->json(array('success' => false, 'error' => $e), 200);
        }
    }

    public function addView(Request $request){
        $this->validate($request, [
            'topic_id' => 'required'
        ]);

        try {
            $increment = ForumPosts::where('id', '=', $request['topic_id'])->increment('views', 1);
            return response()->json(array('success' => true), 200);
        } catch (Exception $e){
            return response()->json(array('success' => false, 'error' => $e), 200);
        }
    }
}