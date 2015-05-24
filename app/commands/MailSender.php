<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class MailSender extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'sendmails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send emails.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire() {
        $i = 1;
        foreach(MailModel::where("lastNotification", "<", Carbon::now()->subMonth(1)->toDateTimeString())->get() as $mail) {
            $this->info('Name: ' . $mail->name);
            $save = false;
            $mail->lastNotification = Carbon::now();
            if($mail->room) {
                $this->info('Room: ' . $mail->room);
                if(count($students = Student::where("room", $mail->room)->where("name", $mail->name)->get())) {
                    $this->info('Match found!');
                    foreach($students as $student) {
                        try {
                            $this->info('Sending to ' . $student->name);
                            if($student->email!="zsombor90@gmail.com") continue;
                            Mail::send(array("text" => 'email'), array('name' => $student->name, 'mailname' => $mail->name), function ($message) use ($student) {
                                $message->to($student->email, $student->name)->subject('Leveled érkezett');
                            });
                            $save = true;
                        } catch(Exception $e) {
                            $this->error('Error: ' . $e->getMessage());
                        }
                    }
                } elseif($students = Student::where("room", $mail->room)->get()) {
                    $this->info('Match not found!');
                    foreach($students as $student) {
                        try {
                            $this->info('Sending to ' . $student->name);
                            if($student->email!="zsombor90@gmail.com") continue;
                            Mail::send(array("text" => 'emailforall'), array('name' => $student->name, 'mailname' => $mail->name), function ($message) use ($student) {
                                $message->to($student->email, $student->name)->subject('Levél érkezett a szobátokba');
                            });
                            $save = true;
                        } catch(Exception $e) {
                            $this->error('Error: ' . $e->getMessage());
                        }
                    }
                }
            } else {
                $this->info('No room!');
                foreach(Student::where("name", $mail->name)->get() as $student) {
                    try {
                        $this->info('Sending to ' . $student->name . ', room:' . $student->room);
                        if($student->email!="zsombor90@gmail.com") continue;
                        Mail::send(array("text" => 'emailforallname'), array('name' => $student->name, 'mailname' => $mail->name), function ($message) use ($student) {
                            $message->to($student->email, $student->name)->subject('Levél érkezett a te neveddel');
                        });
                        $save = true;
                    } catch(Exception $e) {
                        $this->error('Error: ' . $e->getMessage());
                    }
                }
            }
            if($save)
                $mail->save();
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected
    function getArguments() {
        return array();
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected
    function getOptions() {
        return array();
    }

}
