<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Mail;

class MailPrompt extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Mail:Reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mail want to send as a prompt per month';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // $usersMail=User::select('email')->get();
        // $emails=[];
        // foreach($usersMail as $mail)
        // {
        //     $emails[] = $mail['email'];
        // }
        // // $data=array('data'=>'cron testing');
        // Mail::send('emails.promptmail',[], function($message) use($emails){
        //     $message->to($emails)->subject('Laravel Reminder');
        // });
        \Log::info('MailPrompt command started at ' . now());
        $usersMail = User::select('email')->get();
        $emails = [];
        foreach ($usersMail as $mail) {
            $emails[] = $mail['email'];
        }
        \Log::info('Emails to send: ', $emails);
        
        Mail::send('emails.promptmail', [], function ($message) use ($emails) {
            $message->to($emails)->subject('Laravel Reminder');
        });
        \Log::info('MailPrompt command finished at ' . now());
    }
}
