<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class InstallBroadcasting extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'install:broadcasting';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install broadcasting configuration and setup';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Your logic here
        $this->info('Broadcasting setup completed successfully.');
        return 0;
    }
}
