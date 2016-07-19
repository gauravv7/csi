<?php

namespace App\Jobs;

use App\Jobs\Job;
use GuzzleHttp\Client;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNomineeMembershipRejectSms extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $email;
    protected $mobile;
    protected $inst;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email, $mobile, $inst)
    {
        $this->email = $email;
        $this->mobile = $mobile;
        $this->inst = $inst;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {

        $smstext = "You have been rejected as CSI Nominee bearing primary login email as {$this->email} for institution: {$this->inst}. Thankyou. http://www.csi-india.org";
        
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
