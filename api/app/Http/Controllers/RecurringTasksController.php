<?php
namespace App\Http\Controllers;

use App\Models\RecurringTasksModel;
use Illuminate\Http\Request;
use App\Models\User;
use Throwable;
use DateTime;
use Requests;
use App\Models\StartedTasks;
use App\Models\RecurringTasksHistory;

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
            'userId' => 'required',
        ]);
        $timeframe = ['daily', 'weekly', 'monthly', 'quarterly', 'yearly'];
        try {
            // $now = new DateTime();
            // $now = $now->modify('-1 day')->format('Y-m-d');
            $data = Array();
            foreach($timeframe as $timefr){
                $query = RecurringTasksModel::where('user_id', '=', $request['userId'])
                    ->where('recurring', '=', $timefr)
                    ->where('status', "<", 3)
                    //->whereRaw("cast(created_at as date) = '$now'")
                    ->get();
                $data[$timefr] = $query;
            }
            
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

    public function completeTask(Request $request){
        $this->validate($request, [
            'task_id' => 'required'
        ]);

        //try{
            $task = RecurringTasksModel::where('id', '=', $request['task_id'])->get();
            $url = "https://autoliv-eu2.leading2lean.com/api/1.0/dispatches/complete/" . $task[0]['dispatch_id'] . "/";
            $headers = ['Content-type' => 'application/x-www-form-urlencoded'];
            $options = ['auth' => 'ZjbBpxIq0qUYRoEOZkJYlNrEJL5Egkgh', 'site' => 15];

            $l2l_request = Requests::post($url, $headers, $options);

            $response = json_decode($l2l_request->body, true);
            //return response()->json($response, 200);
            if($response['success']){
                $commitTaskToHistory = $this->commitTaskToHistory($task[0]['id']);
                if($commitTaskToHistory){
                    StartedTasks::where('task_id', '=', $task[0]['id'])->delete();
                    RecurringTasksModel::where('id', '=', $request['task_id'])->update(['status' => 3]);
                    return response()->json(array('success' => true), 200);
                }else{
                    return response()->json(array('success' => false, 'error' => 'Couldn\'t commit to history'), 200);
                }
            }else{
                if($response['error'] === "Dispatch is already completed"){
                    $commitTaskToHistory = $this->commitTaskToHistory($task[0]['id']);
                    if($commitTaskToHistory){
                        StartedTasks::where('task_id', '=', $task[0]['id'])->delete();
                        RecurringTasksModel::where('id', '=', $request['task_id'])->update(['status' => 3]);
                        return response()->json(array('success' => true), 200);
                    }else{
                        return response()->json(array('success' => false, 'error' => 'Couldn\'t commit to history'), 200);
                    }
                }
                return response()->json($response, 200);
            }
        // }catch(Throwable $t){
        //     return response()->json(array('success' => false, 'error' => $t), 200);
        // }
    }

    private function commitTaskToHistory($id){
        $recurringTask = RecurringTasksModel::where('id', '=', $id)->get();
        $startedTask = StartedTasks::where('task_id', '=', $id)->get();

        $insert = new RecurringTasksHistory();
        $insert->dispatch_id = $recurringTask[0]['dispatch_id'];
        $insert->user_id = $recurringTask[0]['user_id'];
        $insert->task_id = $id;
        $insert->timeframe = $recurringTask[0]['recurring'];
        $insert->minutesSpent = $startedTask[0]['minutesSpent'];
        $insert->task = $recurringTask[0]['task'];
        if($insert->save()){
            return true;
        }else{
            return false;
        }
    }

    public function activateTask(Request $request){
        $this->validate($request, [
            'taskId' => 'required',
            'status_id' => 'required',
        ]);
        $headers = ['Content-type' => 'application/x-www-form-urlencoded'];

        try {
            
            $task = RecurringTasksModel::where('id', '=', $request['taskId'])->get();
            if($task[0]['dispatch_id'] === null){
                $dispatch = $this->openDispatch($task[0]['task'], $task[0]['recurring'], $task[0]['user_id'], $task[0]['id'], $task[0]['recurring']);
                //print_r($dispatch);
                if($dispatch){
                    RecurringTasksModel::where('id', '=', $request['taskId'])->update(['dispatch_id' => $dispatch]);
                }
            }else {
                $dispatch = $task[0]['dispatch_id'];
            }
            if($request['status_id'] == -4){
                RecurringTasksModel::where('id', '=', $request['taskId'])->update(['status' => 2]);
                $user = User::where('id', '=', $task[0]['user_id'])->get();
                $technicianId = $this->getTechnicianId($user[0]['autoliv_id']);
                //print_r($technicianId);
                if($technicianId){
                    //print_r($technicianId);
                    $url = "https://autoliv-eu2.leading2lean.com/api/1.0/dispatchtechnicians/dispatch/";
                    
                    $options = [
                        'auth' => 'ZjbBpxIq0qUYRoEOZkJYlNrEJL5Egkgh',
                        'site' => 15,
                        'dispatch_id' => $dispatch,
                        'technician_id' => $technicianId
                    ];

                    $l2l_request = Requests::post($url, $headers, $options);
                    $response = json_decode($l2l_request->body, true);
                    //print_r($response);
                    if($response['success']){
                        return response()->json(array('success' => true), 200);
                    }else {
                        if($response['error'] === "technician already dispatched"){
                            $url = "https://autoliv-eu2.leading2lean.com/api/1.0/dispatches/changestatus/" . $dispatch . "/";
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
            }elseif($request['status_id'] == -5){
                RecurringTasksModel::where('id', '=', $request['taskId'])->update(['status' => 0]);
                $url = "https://autoliv-eu2.leading2lean.com/api/1.0/dispatches/changestatus/" . $dispatch . "/";
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
        } catch(Throwable $t){
            return response()->json(array('success' => false, 'error' => $t), 200);
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

    private function openDispatch($task, $type, $userId, $taskId, $timeframe){
        switch($type){
            case 'daily': $machineCode = "Daily Task"; $description = "Daily task: " . $task; break;
            case 'weekly': $machineCode = "Weekly Task"; $description = "Weekly task: " . $task; break;
            case "monthly": $machineCode = "Monthly Task"; $description = "Monthly task: " . $task; break;
            case "quarterly": $machineCode = "Quarterly Task"; $description = "Quarterly task: " . $task; break;
            case "yearly": $machineCode = "Yearly Task"; $description = "Yearly task: " . $task; break;
        }
        $url = "https://autoliv-eu2.leading2lean.com/api/1.0/dispatches/open/";
            $options = [
                'auth' => "ZjbBpxIq0qUYRoEOZkJYlNrEJL5Egkgh",
                'site' => 15,
                'description' => $description,
                'dispatchtypecode' => $machineCode,
                'machinecode' => "IOT-General",
                'tradecode' => 'Others',
            ];

            $headers = ['Content-type' => 'application/x-www-form-urlencoded'];

            $l2l_request = Requests::post($url, $headers, $options);
            $response = json_decode($l2l_request->body, true);

            if($response['success']){
                $startTask = new StartedTasks();
                $startTask->dispatch_id = $response['data']['id'];
                $startTask->user_id = $userId;
                $startTask->task_id = $taskId;
                $startTask->timeframe = $timeframe;
                $startTask->minutesSpent = 0;
                $startTask->save();
                return $response['data']['id'];
            }else {
                return false;
            }
    }
}