<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Services\MailService;


class MailTestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:test 
                            {id : The id of the model to be sent for the booking} 
                            {--template= : The email template you wish to use for the email} 
                            {--theme=chicretreats :  The theming you wish to use for the email} 
                            {--email= : The target email address}';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates and sends an email based on a provided template and any models required';

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
     * @param  \App\DripEmailer  $drip
     * @return mixed
     */

    public function handle() {

        $mailService = new MailService($this->option('theme'));
        $mailService->send($this->argument('id'), $this->option('template'), $this->option('email'));

    }
}
