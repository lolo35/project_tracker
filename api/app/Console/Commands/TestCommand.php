<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class TestCommand extends Command {
    protected $signature = "test:command";
    protected $description = "This is a test command, does whatever I want it too";

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(){
        $mail = new PHPMailer(true);
        $text = "test";
        try{
            $mail -> SMTPDebug = 1;
            $mail -> isSMTP();
            $mail -> Host = "smtp.alv.autoliv.int";
            $mail -> SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            $mail -> setFrom('no-reply@autoliv.com');
            $mail -> addAddress("raul.filimon@autoliv.com", 'Test');
            //$mail -> addCC('raul.filimon@autoliv.com');
            $mail -> isHTML(true);
            $mail -> Subject = "Test";
            $mail -> Body = $text;

            $mail -> send();
        }catch(Exception $e){
            echo "Message error {$e -> ErrorInfo}";
        }
    }
}