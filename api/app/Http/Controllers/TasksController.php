<?php
namespace App\Http\Controllers;
use App\Models\TasksModel;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Requests;
use Illuminate\Support\Facades\DB;
use DateTime;

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

    public function currentlyWorkingTasks(Request $request){
        try {
            $data = DB::table('tasks')
                ->join('users', 'users.id', '=', 'tasks.userId')
                ->select('tasks.*', 'users.name', 'users.email', 'users.autoliv_id')
                ->where('tasks.status', '=', 2)
                ->get();
            return response()->json(array('success' => true, 'data' => $data), 200);
        } catch (Exception $e){
            return response()->json(array('success' => false, 'error' => $e), 200);
        }
    }

    public function addTask(Request $request){
        $this->validate($request, [
            'userId' => 'required',
            'task' => 'required',
            'name' => 'required',
        ]);

        $autolivId = User::where('id', '=', $request['userId'])->get();
        //return response()->json($request['userId'], 200);
        if(!$autolivId->isEmpty()){
            $autolivId = $autolivId[0]['autoliv_id'];

            $url = "https://autoliv-eu2.leading2lean.com/api/1.0/dispatches/open/";
            $options = [
                'auth' => "ZjbBpxIq0qUYRoEOZkJYlNrEJL5Egkgh",
                'site' => 15,
                'description' => $request['task'],
                'dispatchtypecode' => 'test',
                'machinecode' => "TEST 1",
                'tradecode' => 'Others',
            ];

            $headers = ['Content-type' => 'application/x-www-form-urlencoded'];

            $l2l_request = Requests::post($url, $headers, $options);
            $response = json_decode($l2l_request->body, true);

            if($response['success']){
                $url = "https://autoliv-eu2.leading2lean.com/api/1.0/dispatches/changestatus/" . $response['data']['id'] . "/";

                $options = [
                    'auth' => "ZjbBpxIq0qUYRoEOZkJYlNrEJL5Egkgh",
                    'site' => 15,
                    'dispatchstatus_id' => -5
                ];

                $l2l_request = Requests::post($url, $headers, $options);
                $resp = json_decode($l2l_request->body, true);
                if($resp['success']){
                    try {
                        $insert = new TasksModel();
                        $insert->userId = $request['userId'];
                        $insert->task = $request['task'];
                        $insert->status = 0;
                        $insert->dispatch_id = $response['data']['id'];
                        $insert->minutesSpent = 0;
                        $insert->save();
        
                        return response()->json(array('success' => true, 'data' => $insert), 200);
                    } catch (Exception $e){
                        return response()->json(array('success' => false, 'error' => $e), 200);
                    }
                }else{ 
                    return response()->json($resp, 200);
                }
            }
            return response()->json($response, 200);

        }else {
            return response()->json(array('success' => false, 'error' => 'noUser'), 200);
        }
    }

    public function toggleTask(Request $request){
        $this->validate($request, [
            'taskId' => 'required',
            'status_id' => 'required',
        ]);
        try {
            //return response()->json($request, 200);
            $headers = ['Content-type' => 'application/x-www-form-urlencoded'];
            $task = TasksModel::where('id', '=', $request['taskId'])->get();
            if($request['status_id'] == -4){
                TasksModel::where('id', '=', $request['taskId'])->update(['status' => 2]);
                $user = User::where('id', '=', $task[0]['userId'])->get();
                $technicianId = $this->getTechnicianId($user[0]['autoliv_id']);
                if($technicianId){
                    $url = "https://autoliv-eu2.leading2lean.com/api/1.0/dispatchtechnicians/dispatch/";
                    
                    $options = [
                        'auth' => 'ZjbBpxIq0qUYRoEOZkJYlNrEJL5Egkgh',
                        'site' => 15,
                        'dispatch_id' => $task[0]['dispatch_id'],
                        'technician_id' => $technicianId
                    ];

                    $l2l_request = Requests::post($url, $headers, $options);
                    $response = json_decode($l2l_request->body, true);
                    if($response['success']){
                        return response()->json(array('success' => true), 200);
                    }else {
                        if($response['error'] === "technician already dispatched"){
                            $url = "https://autoliv-eu2.leading2lean.com/api/1.0/dispatches/changestatus/" . $task[0]['dispatch_id'] . "/";
                            $options = [
                                'auth' => 'ZjbBpxIq0qUYRoEOZkJYlNrEJL5Egkgh',
                                'site' => 15,
                                'dispatchstatus_id' => $request['status_id']
                            ];

                            $l2l_request = Requests::post($url, $headers, $options);
                            $response = json_decode($l2l_request->body, true);
                            if($response['success']){
                                return response()->json(array('success' => true), 200);
                            }else{
                                return response()->json($response, 200);
                            }
                        }
                    }
                }
            }
            if($request['status_id'] == -5){

                TasksModel::where('id', '=', $request['taskId'])->update(['status' => 0]);
                $url = "https://autoliv-eu2.leading2lean.com/api/1.0/dispatches/changestatus/" . $task[0]['dispatch_id'] . "/";
                $options = [
                    'auth' => 'ZjbBpxIq0qUYRoEOZkJYlNrEJL5Egkgh',
                    'site' => 15,
                    'dispatchstatus_id' => $request['status_id']
                ];

                $l2l_request = Requests::post($url, $headers, $options);
                $response = json_decode($l2l_request->body, true);
                if($response['success']){
                    return response()->json(array('success' => true), 200);
                }else{
                    return response()->json($response, 200);
                }
            }
        } catch (Exception $e) {
            return response()->json(array('success' => false, 'error' => $e), 200);
        }

    }

    private function getTechnicianId($userId){
        $url = "https://autoliv-eu2.leading2lean.com/api/1.0/technicians/?auth=ZjbBpxIq0qUYRoEOZkJYlNrEJL5Egkgh&site=15&technicianid=$userId";
        $request = Requests::get($url);
        $response = json_decode($request->body, true);
        if($response['success']){
            return $response['data'][0]['id'];
        }else{
            return false;
        }
    }

    public function updateTask(Request $request){
        $this->validate($request,[
            'id' => 'required',
            'status' => 'required'
        ]);

        try {
            
            $dispatchId = TasksModel::where('id', '=', $request['id'])->get();
            if(!$dispatchId->isEmpty()){
                $url = "https://autoliv-eu2.leading2lean.com/api/1.0/dispatches/complete/" . $dispatchId[0]['dispatch_id'] . "/";
                $headers = ['Content-type' => 'application/x-www-form-urlencoded'];
                $options = ['auth' => 'ZjbBpxIq0qUYRoEOZkJYlNrEJL5Egkgh', 'site' => 15];

                $l2l_request = Requests::post($url, $headers, $options);

                $response = json_decode($l2l_request->body, true);
                //return response()->json($response, 200);
                if($response['success']){
                    TasksModel::where('id', '=', $request['id'])
                        ->update(
                            [
                                'status' => $request['status']
                            ]
                    );
                    return response()->json(array('success' => true), 200);
                }else {
                    if($response['error'] === "Dispatch is already completed"){
                        TasksModel::where('id', '=', $request['id'])
                            ->update(
                                [
                                    'status' => $request['status']
                                ]
                        );
                        return response()->json(array('success' => true), 200);
                    }                    
                }
            }else {
                return response()->json(array('success' => false, 'error' => 'noTask'), 200);
            }
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