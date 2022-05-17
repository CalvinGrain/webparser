<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Classes\WebParser;

class WebParse extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'web:parse
                            {url : URL that should be parsed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Web parser';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $url = $this->argument('url');

        $parser = new WebParser;
        $results = $parser->parse($url);

        $this->info($results);
    }
}