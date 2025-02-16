<?php

namespace App\Services;

use App\Interfaces\ArticleInterface;
use Illuminate\Support\Facades\Http;
use Exception;
use Illuminate\Support\Facades\Log;

class FetchArticlesFromNewsApi
{
    private $articleRepository;
    
    public function __construct(ArticleInterface $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function fetchArticlesFromAPI()
    {
        $this->getArticlesFromNewsApi();
        $this->getArticlesFromNyTimesApi();
        $this->getArticlesFromGuardianApi();
    }

    private function getArticlesFromNewsApi()
    {
        try {
            $response = Http::get('https://newsapi.org/v2/top-headlines', [
                'apiKey' => env('NEWS_API_KEY'),
                'country' => 'us',
            ]);

            if ($this->checkAPIResponse($response)) {
                $responseBody = json_decode($response->getBody(), true);
                if (isset($responseBody['status']) && $responseBody['status'] == 'ok') {
                    $articles = $responseBody['articles'];
                    $insertData = [];
                    foreach ($articles as $article) {
                        if (is_null($article['title']) || is_null($article['description'])) {
                            continue;
                        }

                        $insertData[] = [
                            'title' => $article['title'],
                            'content' => $article['description'],
                            'source' => 'News API',
                            'category' => $article['category'] ?? null,
                            'image_url' => $article['urlToImage'] ?? null,
                            'author' => $article['author'] ?? null,
                            'published_at' => $article['publishedAt'] ? date('Y-m-d h:i:s' ,strtotime($article['publishedAt'])) : null,
                            "created_at" => date("Y-m-d h:i:s"),
                            "updated_at" => date("Y-m-d h:i:s"),
                        ];
                    }
                    $this->articleRepository->insertArticles($insertData);
                }
            }
        } catch (Exception $e) {dd($e);
            $this->logException($e);
        }
    }

    private function getArticlesFromNyTimesApi()
    {
        try {
            $response = Http::get('https://api.nytimes.com/svc/topstories/v2/home.json', [
                'api-key' => env('NYT_API_KEY')
            ]);

            if ($this->checkAPIResponse($response)) {
                $responseBody = json_decode($response->getBody(), true);
                if (isset($responseBody['status']) && $responseBody['status'] == 'OK') {
                    $articles = $responseBody['results'];
                    $insertData = [];
                    foreach ($articles as $article) {
                        if (is_null($article['title']) || is_null($article['abstract'])) {
                            continue;
                        }

                        $insertData[] = [
                            'title' => $article['title'],
                            'content' => $article['abstract'],
                            'source' => 'New York Times',
                            'category' => $article['subsection'] ?? null,
                            'image_url' => $article['multimedia'][0]['url'] ?? null,
                            'author' => $article['byline'] ?? null,
                            'published_at' => $article['published_date'] ? date('Y-m-d h:i:s' ,strtotime($article['published_date'])) : null,
                            "created_at" => date("Y-m-d h:i:s"),
                            "updated_at" => date("Y-m-d h:i:s"),
                        ];
                    }
                    $this->articleRepository->insertArticles($insertData);
                }
            }
        } catch (Exception $e) {dd($e);
            $this->logException($e);
        }
    }

    private function getArticlesFromGuardianApi()
    {
        try {
            $response = Http::get('https://content.guardianapis.com/search', [
                'api-key' => env('GUARDIAN_API_KEY')
            ]);

            if ($this->checkAPIResponse($response)) {
                $responseBody = json_decode($response->getBody(), true);
                if (isset($responseBody['status']) && $responseBody['status'] == 'ok') {
                    $articles = $responseBody['articles'];
                    $insertData = [];
                    foreach ($articles as $article) {
                        if (is_null($article['webTitle']) || is_null($article['description'])) {
                            continue;
                        }

                        $insertData[] = [
                            'title' => $article['webTitle'],
                            'content' => $article['description'],
                            'source' => 'The Guardian',
                            'category' => $article['sectionName'],
                            'image_url' => null,
                            'author' => null,
                            'webPublicationDate' => $article['publishedAt'] ? date('Y-m-d h:i:s' ,strtotime($article['publishedAt'])) : null,
                            "created_at" => date("Y-m-d h:i:s"),
                            "updated_at" => date("Y-m-d h:i:s"),
                        ];
                    }
                    $this->articleRepository->insertArticles($insertData);
                }
            }
        } catch (Exception $e) {dd($e);
            $this->logException($e);
        }
    }
    
    private function checkAPIResponse ($response) {
        return ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300);
    }

    private function logException(\Throwable $exception)
    {
        Log::error('FILE - ' . $exception->getFile());
        Log::error('FUNCTION - ' . __FUNCTION__);
        Log::error('LINE NO. - ' . $exception->getLine());
        Log::error('Error Message - ' . $exception->getMessage());
        Log::error('Stack trace - ');
        Log::error($exception->getTrace());
    }
}