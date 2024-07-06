<?php

namespace WireComments\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class InstallCommand extends Command
{
    public $signature = 'wire-comments:install';

    public $description = 'Install the WireComments package';

    public function handle(): int
    {
        Artisan::call('vendor:publish', [
            '--provider' => 'WireComments\WireCommentsServiceProvider',
            '--tag' => 'wire-comments-migrations',
        ]);
        $this->info('Provider and migrations published successfully.');

        // Ask if the user wants to publish views using the confirm method
        if ($this->confirm('Do you want to publish the views?')) {
            Artisan::call('vendor:publish', [
                '--tag' => 'wire-comments-views',
            ]);
            $this->info('Views published successfully.');
        }

        // Ask if the user wants to run migrations using the confirm method
        if ($this->confirm('Do you want to run migrations?')) {
            Artisan::call('migrate');
            $this->info('Migrations run successfully.');
        }

        // Publish config file
        Artisan::call('vendor:publish', [
            '--tag' => 'wire-comments-config',
        ]);

        $this->info('Config file published successfully.');

        $this->comment('WireComments installed successfully.');

        return self::SUCCESS;
    }
}
