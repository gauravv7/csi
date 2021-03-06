<?php

namespace App\Jobs;

use App\Jobs\Job;
use GuzzleHttp\Client;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendBulkRegistrationSms extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $email;
    protected $mobile;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email, $mobile)
    {
        $this->email = $email;
        $this->mobile = $mobile;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {

        $smstext = "Applied for CSI bulk payments from account Login ID: {$this->email}. Please make your payments for the same. Thankyou. http://www.csi-india.org";
        
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
