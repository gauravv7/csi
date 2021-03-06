<?php

namespace App\Jobs;

use App\Jobs\Job;
use GuzzleHttp\Client;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendMembershipIdentityAcceptSms extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $id;
    protected $email;
    protected $mobile;
    protected $category;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id, $email, $mobile, $category)
    {
        $this->id = $id;
        $this->email = $email;
        $this->mobile = $mobile;
        $this->category = $category;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {

        $smstext = "Your identity proof for applied CSI registeration category {$this->category}, has been accepted. RequestID: {$this->id}, Login ID: {$this->email}. Thankyou. http://www.csi-india.org";
        
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
