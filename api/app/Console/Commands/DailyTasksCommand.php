<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\RecurringTasksModel;
use App\Models\StartedTasks;
use Requests;
use DateTime;
use Throwable;

class DailyTasksCommand extends Command {
    protected $signature = "daily:tasks";
    protected $description = "Starts daily tasks";

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(){
        $date = new DateTime();
        $date = $date->format("D");
        if($date !== "Sat" || $date !== "Sun"){
            $startedTasks = StartedTasks::where('timeframe', '=', 'daily')->get('task_id')->toArray();
            $startedTaskIds = array();
            foreach($startedTasks as $startedTask){
                $startedTaskIds[] = $startedTask['task_id'];
            }
            $data = RecurringTasksModel::where('recurring', '=', 'daily')->whereNotIn('id', $startedTaskIds)->get()->toArray();
            foreach($data as $task){
                $taskDesc = "Daily task: " . $task['task'];
                try {
                    $url = "https://autoliv-eu2.leading2lean.com/api/1.0/dispatches/open/";
                    $options = [
                        'auth' => "ZjbBpxIq0qUYRoEOZkJYlNrEJL5Egkgh",
                        'site' => 15,
                        'description' => $taskDesc,
                        'dispatchtypecode' => 'Daily Task',
                        'machinecode' => "IOT-General",
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
                            RecurringTasksModel::where('id', '=', $task['id'])->update(['dispatch_id' => $response['data']['id'], 'status' => 0]);
                            $startTask = new StartedTasks();
                            $startTask->dispatch_id = $response['data']['id'];
                            $startTask->user_id = $task['user_id'];
                            $startTask->task_id = $task['id'];
                            $startTask->timeframe = "daily";
                            $startTask->minutesSpent = 0;
                            $startTask->save();
                            $this->info($task['task'] . " -  success");
                        }else{
                            $this->error($resp['error']);
                        }
                    }else{
                        $this->error($response['error']);
                    }
                } catch(Throwable $t){
                    print_r($t);
                }
            }   
        }
    }
}