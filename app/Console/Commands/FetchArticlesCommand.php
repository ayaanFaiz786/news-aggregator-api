<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FetchArticlesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-articles-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to fetch articles from multiple news api';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        app('App\Services\FetchArticlesFromNewsApi')->fetchArticlesFromAPI();
    }
}
