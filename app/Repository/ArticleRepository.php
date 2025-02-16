<?php

namespace App\Repository;

use App\Interfaces\ArticleInterface;
use App\Models\Article;

class ArticleRepository implements ArticleInterface
{
    public function getAllArticles($request)
    {
        $articles = Article::query();

        if ($request->has('keyword')) {
            $articles = $this->filterByKeyword($articles, $request->keyword);
        }

        if ($request->has('date')) {
            $articles = $this->filterByDate($articles, $request->date);
        }

        if ($request->has('category')) {
            $articles = $this->filterByCategory($articles, $request->category);
        }

        if ($request->has('source')) {
            $articles = $this->filterBySource($articles, $request->source);
        }
        return $articles->paginate(10);
    }

    private function filterByKeyword($query, $keyWord)
    {
        return $query->where('title', 'like', '%' . $keyWord . '%')
        ->orWhere('content', 'like', '%' . $keyWord . '%');
    }

    private function filterByDate($query, $date)
    {
        return $query->whereDate('published_at', $date);
    }

    private function filterByCategory($query, $category)
    {
        if (is_array($category)) {
            return $query->whereIn('category', $category);
        }
        return $query->where('category', $category);
    }

    private function filterBySource($query, $source)
    {
        if (is_array($source)) {
            return $query->whereIn('source', $source);
        }
        return $query->where('source', $source);
    }

    private function filterByAuthor($query, $author)
    {
        if (is_array($author)) {
            return $query->whereIn('author', $author);
        }
        return $query->where('author', $author);
    }

    public function getArticleById($id)
    {
        return Article::findOrFail($id);
    }

    public function insertArticles(array $data)
    {
        return Article::insert($data);
    }

    public function getPreferredArticles($user)
    {
        $preferences = $user->preferences;
        $articlesQuery = Article::query();

        // Filter by sources
        if ($preferences && !empty($preferences->sources)) {
            $this->filterBySource($articlesQuery, json_decode($preferences->sources, true));
        }

        // // Filter by categories
        if ($preferences && !empty($preferences->categories)) {
            $this->filterByCategory($articlesQuery, json_decode($preferences->categories, true));
        }

        // // Filter by authors
        if ($preferences && !empty($preferences->authors)) {
            $this->filterByAuthor($articlesQuery, json_decode($preferences->authors, true));
        }
        return $articlesQuery->paginate(10);
    }
}
