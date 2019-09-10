<?php

namespace App\Commands;

use App\Handlers\CakeHandler;
use Illuminate\Support\Facades\Storage;
use LaravelZero\Framework\Commands\Command;

class Cake extends Command
{

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'cake {list=data.txt}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @param \App\Handlers\CakeHandler $handler
     * @return mixed
     */
    public function handle(CakeHandler $handler)
    {
        $peopleStr = empty($this->argument('list'))?Storage::get('data.txt'):
            Storage::get($this->argument('list'));
        $data      = array_filter(explode("\n", $peopleStr));
        $output    = $handler->run($data);
        $this->info($output->implode(PHP_EOL));
    }
}
