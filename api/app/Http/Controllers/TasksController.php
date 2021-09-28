<?php
namespace App\Http\Controllers;
use App\Models\TasksModel;
use Exception;
use Illuminate\Http\Request;

class TasksController extends Controller {

    public function getTasks(Request $request){
        $this->validate($request, [
            'userId' => 'required'
        ]);

        try {
            $data = TasksModel::where('userId', '=', $request['userId'])->get();
            return response()->json(array('success' => true, 'data' => $data), 200);
        } catch (Exception $e){
            return response()->json(array('success' => false, 'error' => $e), 200);
        }
    }

    public function addTask(Request $request){
        $this->validate($request, [
            'userId' => 'required',
            'task' => 'required',
        ]);

        try {
            $insert = new TasksModel();
            $insert->userId = $request['userId'];
            $insert->task = $request['task'];
            $insert->status = 0;
            $insert->save();

            return response()->json(array('success' => true, 'data' => $insert), 200);
        } catch (Exception $e){
            return response()->json(array('success' => false, 'error' => $e), 200);
        }
    }

    public function updateTask(Request $request){
        $this->validate($request,[
            'id' => 'required',
            'status' => 'required'
        ]);
        try {
            TasksModel::where('id', '=', $request['id'])
                ->update(
                    [
                        'status' => $request['status']
                    ]
            );
            return response()->json(array('success' => true), 200);
        } catch (Exception $e){
            return response()->json(array('success' => false, 'error' => $e), 200);
        }
    }

    public function deleteTask(Request $request){
        $this->validate($request, [
            'id' => 'required'
        ]);

        try {
            TasksModel::where('id', '=', $request['id'])->delete();
            return response()->json(array('success' => true), 200);
        } catch (Exception $e){
            return response()->json(array('success' => false, 'error' => $e), 200);
        }
    }

}