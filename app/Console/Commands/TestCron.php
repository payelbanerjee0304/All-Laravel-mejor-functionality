<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Mail;


class TestCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $usersMail=User::select('email')->get();
        $emails=[];
        foreach($usersMail as $mail)
        {
            $emails[] = $mail['email'];
        }
        // $data=array('data'=>'cron testing');
        Mail::send('emails.crontest',[], function($message) use($emails){
            $message->to($emails)->subject('crontest.example');
        });
    }
}
