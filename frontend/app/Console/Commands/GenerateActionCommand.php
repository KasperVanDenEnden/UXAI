<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateActionCommand extends Command
{
    protected $signature = 'make:action {name}';

    protected $description = 'Generate action';

    public function handle(): int
    {
        $name = $this->argument('name');

        $className = ucfirst($name).'Action';

        $path = app_path("Actions/{$className}.php");

        if (file_exists($path)) {
            $this->error('Action already exists!');

            return Command::FAILURE;
        }

        $stub = file_get_contents(base_path('stubs/action.stub'));

        if (! $stub) {
            $this->error('Action.stub not found!');

            return Command::FAILURE;
        }

        $stub = str_replace('{{className}}', $className, $stub);

        file_put_contents($path, $stub);

        $this->info('Action created successfully.');

        return Command::SUCCESS;
    }
}
