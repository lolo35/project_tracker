<?php
namespace App\Http\Controllers;

use App\Models\RecurringTasksModel;
use Illuminate\Http\Request;
use Throwable;
use DateTime;

class RecurringTasksController extends Controller {
    public function getTasks(Request $request){
        $this->validate($request, [
            'userId' => 'required',
        ]);

        try {
            $data = RecurringTasksModel::where('user_id', '=', $request['userId'])->get();
            return response()->json(array('success' => true, 'data' => $data), 200);
        } catch (Throwable $t){
            return response()->json(array('success' => false, 'error' => $t), 200);
        }
    }
    public function getDailyTasks(Request $request){
        $this->validate($request, [
            'userId' => 'required'
        ]);

        try {
            $now = new DateTime();
            $now = $now->modify('-1 day')->format('Y-m-d');
            $data = RecurringTasksModel::where('user_id', '=', $request['userId'])
                ->where('recurring', '=', 'daily')
                ->whereRaw("cast(created_at as date) = '$now'")
                ->get();
            
            return response()->json(array('success' => true, 'data' => $data), 200);
        } catch(Throwable $t){
            return response()->json(array('success' => false, 'error' => $t), 200);
        }
    }

    public function addTask(Request $request){
        $this->validate($request, [
            'userId' => 'required',
            'occurence' => 'required',
            'task' => 'required',
        ]);

        try {
            $insert = new RecurringTasksModel();
            $insert->user_id = $request['userId'];
            $insert->task = $request['task'];
            $insert->status = 0;
            //$insert->description
            $insert->recurring = $request['occurence'];
            $insert->save();

            return response()->json(array('success' => true, 'data' => $insert), 200);
        } catch (Throwable $t){
            return response()->json(array('success' => false, 'error' => $t), 200);
        }
    }

    public function deleteTask(Request $request){
        $this->validate($request, [
            'id' => 'required'
        ]);

        try {
            RecurringTasksModel::where('id', '=', $request['id'])->delete();
            return response()->json(array('success' => true), 200);
        }catch (Throwable $t){
            return response()->json(array('success' => false, 'error' => $t), 200);
        }
    }

    public function activeTask(Request $request){
        $this->validate($request, [
            'taskId' => 'required'
        ]);

        try {
            RecurringTasksModel::where('id', '=', $request['taskId'])->update(['status' => 2]);
            return response()->json(array('success' => true), 200);
        } catch(Throwable $t){
            return response()->json(array('success' => false, 'error' => $t), 200);
        }
    }
}