<?php
namespace App\Http\Controllers;

use App\Models\ProjectsModel;
use Exception;
use Illuminate\Http\Request;

class ProjectsController extends Controller {
    public function getProjects(){
        try {
            $data = ProjectsModel::get();
            return response()->json(array('success' => true, 'data' => $data), 200);
        } catch (Exception $e){
            return response()->json(array('success' => false, 'error' => $e), 200);
        }
    }

    public function addProject(Request $request){
        $this->validate($request, [
            'project' => 'required',
            'description' => 'required'
        ]);

        try{
            $insert = new ProjectsModel();
            $insert->name = $request['project'];
            $insert->description = $request['description'];
            $insert->save();
            return response()->json(array('success' => true, 'data' => $insert), 200);
        } catch (Exception $e){
            return response()->json(array('success' => false, 'error' => $e), 200);
        }
    }
}