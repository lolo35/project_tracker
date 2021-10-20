<?php
namespace App\Http\Controllers;
use App\Models\ForumTopics;
use App\Models\ForumCategory;
use Exception;
use Illuminate\Http\Request;

class ForumTopicController extends Controller {
    public function getTopicsForCategory(Request $request){
        $this->validate($request, [
            'category_id' => 'required'
        ]);

        try {
            $data = ForumTopics::where('category_id', '=', $request['category_id'])->get();
            return response()->json(array('success' => true, 'data' => $data), 200);
        } catch (Exception $e){
            return response()->json(array('success' => false, 'error' => $e), 200);
        }
    }

    public function getTopic(Request $request){
        $this->validate($request, [
            'topic_id' => 'required'
        ]);

        try {
            $data = ForumTopics::where('id', '=', $request['topic_id'])->get();
            return response()->json(array('success' => true, 'data' => $data), 200);
        } catch (Exception $e){
            return response()->json(array('success' => false, 'error' => $e), 200);
        }
    }
}