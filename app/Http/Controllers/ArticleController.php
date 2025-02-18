<?php

namespace App\Http\Controllers;

use App\Helper\ApiResponse;
use App\Interfaces\ArticleInterface;
use Exception;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    private $articleRepository;
    
    public function __construct(ArticleInterface $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    /**
     * @description Fetch all the articles or filter based from the repository.
     * @param Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $articles = $this->articleRepository->getAllArticles($request);
            return ApiResponse::success(self::SUCCESS_STATUS, 'Articles fetched successfully', $articles, 200);
        } catch (Exception $e) {
            logException($e);
            return ApiResponse::error(self::ERROR_STATUS, $e->getMessage(), 500);
        }
    }

    /**
     * @description Fetch single article details.
     * @param integer $id
     * @return Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $article = $this->articleRepository->getArticleById($id);

            if (!$article) {
                return ApiResponse::error(self::ERROR_STATUS, 'Article not found', [], 404);
            }
            return ApiResponse::success(self::SUCCESS_STATUS, 'Article fetched successfully', $article, 200);
        } catch (Exception $e) {
            logException($e);
            return ApiResponse::error(self::ERROR_STATUS, $e->getMessage(), 500);
        }
    }

    /**
     * @description Fetch single based on users preferences.
     * @param Request $request
     * @return Illuminate\Http\JsonResponse
     */
    public function personalizedFeed(Request $request)
    {
        try {
            $user = $request->user();
            $articles = $this->articleRepository->getPreferredArticles($user);
            
            return ApiResponse::success(self::SUCCESS_STATUS, 'Personalised articles fetched successfully', $articles, 200);
        } catch (Exception $e) {
            logException($e);
            return ApiResponse::error(self::ERROR_STATUS, $e->getMessage(), 500);
        }
    }
}
