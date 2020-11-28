<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;

class ShareFileEmail extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $sharer;
    public $email_to;
    public $file;

    public function __construct($sharer, $email_to, $file)
    {
       $this->sharer = $sharer;
       $this->email_to = $email_to;
       $this->file = $file;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email_to = $this->email_to;
        $file = $this->file;

        Mail::send('email.sent-file', ['user' => $this->sharer], function ($m) use ($email_to, $file) {
            $m->attach(public_path().DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.$file );
            $m->to($email_to)->subject('File shared with you');
        });
        
    }
}
