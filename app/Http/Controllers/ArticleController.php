<?php

namespace App\Http\Controllers;

use App\Interfaces\ArticleInterface;
use App\Models\Article;
use Exception;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    private $articleRepository;
    
    public function __construct(ArticleInterface $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function index(Request $request)
    {
        try {
            $articles = $this->articleRepository->getAllArticles($request);
            return response()->json($articles);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Something went wrong',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $article = $this->articleRepository->getArticleById($id);
            return response()->json($article);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Something went wrong',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function personalizedFeed(Request $request)
    {
        try {
            $user = $request->user();
            $articles = $this->articleRepository->getPreferredArticles($user);
            
            return response()->json($articles);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Something went wrong',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
