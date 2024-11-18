<?php

namespace App\Console\Commands;

use App\Models\Customer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendAttendanceReminderEmailToParent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:send-attendance-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send an attendance reminder email to parents on the last day of every month';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $customers=Customer::all()->unique('email');

    }
}
