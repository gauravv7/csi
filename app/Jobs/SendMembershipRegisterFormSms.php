<?php

namespace App\Jobs;

use App\Jobs\Job;
use GuzzleHttp\Client;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendMembershipRegisterFormSms extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $id;
    protected $email;
    protected $mobile;
    protected $category;
    protected $password;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id, $email, $mobile, $category, $password)
    {
        $this->id = $id;
        $this->email = $email;
        $this->mobile = $mobile;
        $this->password = $password;
        $this->category = $category;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {

        $smstext = "Applied CSI registeration for category {$this->category}. RequestID: {$this->id}, Login ID: {$this->email} and password {$this->password}. Please make your payments. Thankyou. http://www.csi-india.org";
        
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
