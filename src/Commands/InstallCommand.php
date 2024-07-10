<?php

namespace WireComments\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Process\Process;

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

        // Ask if the user wants to install dayjs
        if ($this->confirm('Do you want to install dayjs?')) {
            // Ask if the user wants to use npm or yarn
            $packageManager = $this->choice('Which package manager do you want to use?', ['npm', 'yarn'], 0);
            $this->installDayjs($packageManager);
        }

        $this->comment('WireComments installed successfully.');

        return self::SUCCESS;
    }

    protected function installDayjs(string $packageManager): void
    {
        $command = $packageManager === 'npm' ? ['npm', 'install', 'dayjs'] : ['yarn', 'add', 'dayjs'];
        $process = new Process($command);
        $process->setTty(true);
        $process->run(function ($type, $buffer) {
            echo $buffer;
        });

        if ($process->isSuccessful()) {
            $this->info('dayjs installed successfully.');
        } else {
            $this->error('Failed to install dayjs.');
        }

        // Run npm install or yarn install
        $installCommand = $packageManager === 'npm' ? ['npm', 'install'] : ['yarn', 'install'];
        $process = new Process($installCommand);
        $process->setTty(true);
        $process->run(function ($type, $buffer) {
            echo $buffer;
        });

        if ($process->isSuccessful()) {
            $this->info('Dependencies installed successfully.');
        } else {
            $this->error('Failed to install dependencies.');

            $this->error($process->getErrorOutput());
        }
    }
}
