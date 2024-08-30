<?php

namespace App\Jobs;

use App\Mail\SendMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class SendMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public $timeout = 60;
    protected $data;
    /**
     * Create a new job instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Validate email before sending
            $validator = Validator::make($this->data, [
                'email' => 'required|email',
            ]);
            if ($validator->fails()) {
                throw new \Exception("Invalid email address: " . $this->data['email']);
            }

            Mail::to($this->data['email'])->queue(new SendMail($this->data));
        } catch (\Exception $e) {
            // Trigger a job failure
            Log::error("Error in SendMailJob: " . $e->getMessage());
            $this->fail($e);
        }
    }
    public function failed(\Exception $exception)
    {
        Log::info("message not sent to $this->data['email'] due to $exception");
    }
}
