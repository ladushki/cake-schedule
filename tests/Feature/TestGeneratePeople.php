<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TestGeneratePeople extends TestCase
{

    public function testGenerateCommand()
    {
        $this->artisan('generate-people', ['number' => 2, 'file' => 'test.txt'])
            ->expectsOutput('File is generated')
             ->assertExitCode(0);
    }
    public function testGeneratePeople()
    {
        Storage::fake('local');
        $this->artisan('generate-people', ['number' => 2, 'file' => 'test.txt'])->assertExitCode(0);
        Storage::disk('local')->assertExists('test.txt');
        $content = Storage::disk('local')->get('test.txt');

        $this->assertNotEmpty($content);
        $cnt = count(explode(PHP_EOL, $content));
        $this->assertEquals(2, $cnt);
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }
}
