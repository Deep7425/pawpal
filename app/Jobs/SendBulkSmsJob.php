<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendBulkSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $mobileNumbers;
    protected $data;

    /**
     * Create a new job instance.
     *
     * @param array $mobileNumbers
     * @param array $data
     * @return void
     */
    public function __construct(array $mobileNumbers, array $data) {
        $this->mobileNumbers = $mobileNumbers;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {
        $apiKey = env('NEXMO_API_KEY');
        $apiSecret = env('NEXMO_API_SECRET');
        $from = 'YourSenderID'; // Replace with your desired sender ID
        $client = new \Nexmo\Client(new \Nexmo\Client\Credentials\Basic($apiKey, $apiSecret));
        foreach ($this->mobileNumbers as $mobileNumber) {
            // Send SMS to each mobile number
            $message = $client->message()->send([
                'to' => $mobileNumber,
                'from' => $from,
                'text' => $this->data['msg'],
            ]);
            // Process the response or perform any necessary actions
            if ($message['status'] == 0) {
               echo "successfully sent";
            } else {
                echo "error";
            }
        }
		return $this->view();
    }
}
