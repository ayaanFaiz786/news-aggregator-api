<?php

namespace App\Console\Commands;

use App\Services\FetchArticlesFromNewsApi;
use Illuminate\Console\Command;

class FetchArticlesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'news-aggregator:fetch-articles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to fetch articles from multiple news api';

    public function __construct(private FetchArticlesFromNewsApi $fetchArticlesFromNewsApi)
    {
        parent::__construct();
    }
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->fetchArticlesFromNewsApi->fetchArticlesFromAPI();
    }
}
