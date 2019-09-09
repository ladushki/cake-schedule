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
    protected $signature = 'cake';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(CakeHandler $handler)
    {
        $peopleStr = Storage::get('data.txt');
        $data      = array_filter(explode("\n", $peopleStr));
        $output    = $handler->run($data);
        $this->info($output->implode(PHP_EOL));
        $this->info($output->implode(PHP_EOL));
    }
}
