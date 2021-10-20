<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\User;

class CreateUsers extends Command {
    protected $signature = "users:create";
    protected $description = "Created dummy users from app";

    public function __construct()
    {
        parent::__construct();   
    }

    public function handle(){
        $users = ['Paul Ianas', 'Alin Selariu', 'Darth Vader', 'Princess Leia', 'Filimon Raul'];
        $password = hash("sha512", "Autoliv01@");
        $email = "raul.filimon@autoliv.com";

        $bar = $this->output->createProgressBar(count($users));

        foreach($users as $user){
            $insert = new User();
            $insert->name = $user;
            $insert->email = $email;
            $insert->password = $password;
            $insert->autoliv_id = "TRO07513";

            $insert->save();
            $bar->advance();
        }

        $bar->finish();
        $this->info("Done");
    }
}