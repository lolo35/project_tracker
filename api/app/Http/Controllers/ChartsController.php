<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Issues;
use DateTime;
use App\Models\TasksModel;
use App\Models\ProjectsModel;
use Illuminate\Support\Facades\DB;
use Exception;

class ChartsController extends Controller {
    public function countTasks(){
        try {
            $doneTasks = TasksModel::selectRaw("count(id) as total")->where('status', '=', 1)->get();
            $idleTasks = TasksModel::selectRaw("count(id) as total")->where('status', '=', 0)->get();
            $workingTasks = TasksModel::selectRaw("count(id) as total")->where('status', '=', 2)->get();
            return response()->json(array('success' => true, 'doneTasks' => $doneTasks, 'idleTasks' => $idleTasks, 'workingTasks' => $workingTasks), 200);
        } catch (Exception $e){
            return response()->json(array('success' => false, 'error' => $e), 200);
        }

    }

    public function taskCountEvolution(){
        try {
            $date = new DateTime();
            $date = $date->modify('-7 days')->format("Y-m-d");

            $doneTasks = TasksModel::where('status', '=', 1)->where('created_at', '>=', $date)->get()->toArray();
            $donetasksArr = [];
            foreach($doneTasks as $task){
                $taskDate = new DateTime($task['created_at']);
                $taskDate = $taskDate->format("Y-m-d");
                if(!array_key_exists($taskDate, $donetasksArr)){
                    $donetasksArr[$taskDate] = 1;
                }else{
                    $donetasksArr[$taskDate] += 1;
                }
            }

            return response()->json(array('success' => true, 'doneTasks' => $donetasksArr), 200);
        } catch (Exception $e){
            return response()->json(array('success' => false, 'error' => $e), 200);
        }
    }

    public function latestIssues(){
        try {
            //$data = Issues::where('status', '=', 1)->orderBy('created_at', 'desc')->limit(5)->get();
            $data = DB::table('issues')
                ->join('projects', 'issues.project_id', '=', 'projects.id')
                ->join('users', 'issues.opened_by', '=', 'users.id')
                ->select('issues.*', 'projects.name', 'users.name as openedBy')
                ->where('issues.status', '=', 1)
                ->orderBy('issues.created_at', 'desc')
                ->limit(5)
                ->get();
            return response()->json(array('success' => true, 'data' => $data), 200);
        } catch (Exception $e){
            return response()->json(array('success' => false, 'error' => $e), 200);
        }
    }

    public function countIssues(){
        try {
            $openIssues = Issues::selectRaw('count(id) as total')->where('status', '=', 1)->get();
            $closedIssues = Issues::selectRaw('count(id) as total')->where('status', '=', 0)->get();
            return response()->json(array('success' => true, 'openIssues' => $openIssues, 'closedIssues' => $closedIssues), 200);
        } catch (Exception $e){
            return response()->json(array('success' => false, 'error' => $e), 200);
        } 
    }

    public function latestProjects(){
        try{
            $data = ProjectsModel::orderBy('created_at', 'desc')->limit(5)->get();
            return response()->json(array('success' => true, 'data' => $data), 200);
        } catch (Exception $e){
            return response()->json(array('success' => false, 'error' => $e), 200);
        }
    }
}