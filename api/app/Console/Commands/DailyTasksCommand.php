<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\RecurringTasksModel;
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
        $data = RecurringTasksModel::where('recurring', '=', 'daily')->get()->toArray();
        foreach($data as $task){
            $taskDesc = "Daily task: " . $task['task'];
            try {
                $url = "https://autoliv-eu2.leading2lean.com/api/1.0/dispatches/open/";
                $options = [
                    'auth' => "ZjbBpxIq0qUYRoEOZkJYlNrEJL5Egkgh",
                    'site' => 15,
                    'description' => $taskDesc,
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
                        RecurringTasksModel::where('id', '=', $task['id'])->update(['dispatch_id' => $response['data']['id']]);
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