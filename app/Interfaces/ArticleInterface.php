<?php

namespace App\Interfaces;

interface ArticleInterface
{
    public function getAllArticles($request);
    public function getArticleById($id);
    public function insertArticles(array $data);
    public function getPreferredArticles($user);
}
