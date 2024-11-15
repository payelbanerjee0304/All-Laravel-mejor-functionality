<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Twilio\Rest\Client;
use Twilio\Http\CurlClient;

class SendSms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send SMS to users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::select('phone')->get();

        $twilioSid = config('services.twilio.sid');
        $twilioAuthToken = config('services.twilio.token');
        $twilioPhoneNumber = config('services.twilio.phone_number');

        $httpClient = new CurlClient([
            CURLOPT_CAINFO => 'C:\xampp\php831new\extras\ssl\cacert.pem'
        ]);

        $client = new Client($twilioSid, $twilioAuthToken, null, null, $httpClient);

        foreach ($users as $user) {
            if (!is_null($user->phone) && is_string($user->phone)) {
                $client->messages->create(
                    $user->phone,
                    [
                        'from' => $twilioPhoneNumber,
                        'body' => 'This is a test SMS from Laravel All in One'
                    ]
                );
            }else{
                $this->error("Invalid phone number for user ID {$user->id}");
            }
        }

        $this->info('SMS sent successfully');
    }
}
