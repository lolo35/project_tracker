<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;

class FizzBuzz extends Command {
    protected $signature = "fizz:buzz";
    protected $description = "Classic fizz buzz coding puzzle";

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(){
        $this->info("Starting fizz buzz");

        for($i = 0; $i < 100; $i++){
            switch($i){
                case $i % 3 === 0: $this->info("Fizz: " . $i . " / 3");
                case $i % 5 === 0: $this->info("Buzz: " . $i . " / 5");
                case $i % 3 === 0 && $i %5 == 0: $this->info("FizzBuzz: " . $i . " / 3 and 5");
            }
        }
    }
}