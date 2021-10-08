<?php
namespace App\Http\Controllers;

use App\Models\ProjectsModel;
use App\Models\TasksModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function getTasksForProject(Request $request){
        $this->validate($request, [
            'projectId' => 'required'
        ]);

        try {
            //$data = TasksModel::where('project_id', '=', $request['projectId'])->get();
            $data = DB::table('tasks')
                ->join('users', 'users.id', '=', 'tasks.userId')
                ->join('projects', 'tasks.project_id', '=', 'projects.id')
                ->select('tasks.*', 'users.id as userId', 'users.name as username', 'projects.name')
                ->where('tasks.project_id', '=', $request['projectId'])
                ->get();
            return response()->json(array('success' => true, 'data' => $data), 200);
        } catch (Exception $e){
            return response()->json(array('success' => false, 'error' => $e), 200);
        }
    }

    public function percentDone(Request $request){
        $this->validate($request, [
            'projectId' => 'required'
        ]);

        try {
            $totalTasks = TasksModel::selectRaw('count(id) as total')
                ->where('project_id', '=', $request['projectId'])
                ->get();
            if($totalTasks[0]['total'] > 0){
                $doneTasks = TasksModel::selectRaw('count(id) as total')
                    ->where('project_id', '=', $request['projectId'])
                    ->where('status', '=', 1)
                    ->get();
                $totalTime = TasksModel::selectRaw('sum(minutesSpent) as totalTime')
                    ->where('project_id', '=', $request['projectId'])
                    ->get();
                $percentDone = ($doneTasks[0]['total'] / $totalTasks[0]['total']) * 100;
                return response()->json(
                    array(
                        'success' => true,
                        'doneTasks' => $doneTasks, 
                        'totalTasks' => $totalTasks,
                        'percent' => number_format($percentDone, 2, '.', ''),
                        'totalTime' => $totalTime[0]['totalTime'],
                    ), 
                200);
            }else{
                return response()->json(array('success' => false, 'error' => 'noTasks'), 200);
            }
        } catch (Exception $e){
            return response()->json(array('success' => false, 'error' => $e), 200);
        }
    }
}