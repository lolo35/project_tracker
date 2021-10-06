<?php
namespace App\Console\Commands;

use DateTime;
use Illuminate\Console\Command;
use App\Models\TasksModel;
use Exception;

class DateTimeDiff extends Command {
    protected $signature = "time:diff";
    protected $description = "Calculates time spent on job";

    public function __construct()
    {
        parent::__construct();   
    }

    public function handle(){
        $this->info('Starting time:diff');
        while(true){
            
            try {
                $data = TasksModel::where('status', '=', 2)->get()->toArray();
                $bar = $this->output->createProgressBar(count($data));
                foreach($data as $row){
                    $minutes = $row['minutesSpent'] + 1;
                    TasksModel::where('id', '=', $row['id'])->update(['minutesSpent' => $minutes]);
                    $bar->advance();
                }
                $bar->finish();
            } catch (Exception $e){
                $this->error($e);
            }            
            sleep(60);
        }

    }
}