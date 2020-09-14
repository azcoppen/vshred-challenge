<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

use App\Models\User;

class Setup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs install commands';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    private function external ($cmd)
    {
        $process = new Process($cmd);

		$process->run();

		if (!$process->isSuccessful()) {
		    throw new ProcessFailedException($process);
		}

    	echo $process->getOutput();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $this->call ('migrate');
        $this->call ('db:seed');
        $this->call ('passport:install', ['--force' => 'default']);

        User::all()->each(function ($item, $key) {
            $this->line ("User ".$item->id." (".$item->email.") access token: ".$item->createToken ('Personal Access Token')->plainTextToken);
        });

        $this->info ('All done. Passwords are all 12345. Point Postman/Insomnia at '.route ('users.index'));
        $this->line ("Don't use OAuth. Use JWT: https://jwt-auth.readthedocs.io/en/develop/quick-start/");
        return 0;
    }
}
