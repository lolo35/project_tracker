<?php
namespace App\Http\Controllers;

use App\Models\ProjectsModel;
use App\Models\ProjectPriorityHistory;
use App\Models\TasksModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectsController extends Controller {
    public function getProjects(){
        try {
            $data = ProjectsModel::orderBy('priority', 'asc')->get();
            return response()->json(array('success' => true, 'data' => $data), 200);
        } catch (Exception $e){
            return response()->json(array('success' => false, 'error' => $e), 200);
        }
    }

    public function addProject(Request $request){
        $this->validate($request, [
            'project' => 'required',
            'description' => 'required',
            'priority' => 'required',
            'comment' => 'required',
        ]);

        try{
            $insert = new ProjectsModel();
            $insert->name = $request['project'];
            $insert->description = $request['description'];
            $insert->priority = $request['priority'];
            $insert->save();

            $history = new ProjectPriorityHistory();
            $history->project_id = $insert['id'];
            $history->priority = $request['priority'];
            $history->comment = $request['comment'];
            $history->save();

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
                ->select('tasks.*', 'users.id as userId', 'users.name as username', 'projects.name', 'projects.priority')
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

    public function changePriority(Request $request){
        $this->validate($request, [
            'reason' => 'required',
            'newPriority' => 'required',
            'project_id' => 'required',
        ]);

        try {
            $insert = new ProjectPriorityHistory();
            $insert->project_id = $request['project_id'];
            $insert->priority = $request['newPriority'];
            $insert->comment = $request['reason'];
            if($insert->save()){
                ProjectsModel::where('id', '=', $request['project_id'])->update(['priority' => $request['newPriority']]);
                return response()->json(array('success' => true), 200);
            }

        } catch (Exception $e){
            return response()->json(array('success' => false, 'error' => $e), 200);
        }
    }

    public function getProjectHistory(Request $request){
        $this->validate($request, [
            'project_id' => 'required'
        ]);

        try {
            $data = ProjectPriorityHistory::where('project_id', '=', $request['project_id'])->orderBy('created_at', 'desc')->limit(5)->get();
            return response()->json(array('success' => true, 'data' => $data), 200);
        } catch (Exception $e){
            return response()->json(array('success' => false, 'error' => $e), 200);
        }
    }

    public function getProjectById(Request $request){
        $this->validate($request, [
            'project_id' => 'required'
        ]);

        try {
            $data = ProjectsModel::where('id', '=', $request['project_id'])->get();
            return response()->json(array('success' => true, 'data' => $data), 200);
        } catch (Exception $e){
            return response()->json(array('success' => false, 'error' => $e), 200);
        }
    }
}