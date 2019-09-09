<?php

namespace App\Commands;

use Faker;
use Illuminate\Support\Facades\Storage;
use LaravelZero\Framework\Commands\Command;

class GeneratePeople extends Command
{

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'generate-people {number=20} {file=data.txt}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Generate employees';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->info('Generate');
        $faker = Faker\Factory::create();
        $data  = [];
        $cnt   = $this->argument('number');
        for ($i = 0; $i < $cnt; $i++) {
            $data[] = $faker->name . ', ' . $faker->dateTimeBetween($startDate = '-60 years', $endDate = 'now')
                                                  ->format('Y-m-d');
        }
        Storage::put($this->argument('file'), implode("\n", $data));
        $this->info('File is generated');
    }
}
