<?php
namespace App\Http\Controllers;
use App\Models\ForumCategory;
use Illuminate\Http\Request;
use Exception;

class ForumCategoryController extends Controller {
    public function getCategories(){
        try {
            $data = ForumCategory::get();
            return response()->json(array('success' => true, 'data' => $data), 200);
        } catch (Exception $e){
            return response()->json(array('success' => false, 'error' => $e), 200);
        }
    }

}
