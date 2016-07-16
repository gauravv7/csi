<?php

namespace App\Jobs;

use App\Jobs\Job;
use GuzzleHttp\Client;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendVerificationSms extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;



    protected $id;
    protected $email;
    protected $mobile;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id, $email, $mobile)
    {
        $this->id = $id;
        $this->email = $email;
        $this->mobile = $mobile;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $smstext = "Congrats for joining CSI as its esteemed member. Your Membership Number is {$this->id}, please check your Primary Email ID {$this->email} for further details. - CSI";
        
        $client = new Client();
        $res = $client->request('POST', 'http://203.212.70.200/smpp/sendsms', [
            'form_params' => [
                'username' => 'bhartiv', 
                'password' => 'del12345',
                'from' => 'BVICAM',
                'to' => $this->mobile,
                'text' => $smstext
            ]
        ]);
    }
}
