<?php
// src/AppBundle/Controller/ArticleController.php
//namespace AppBundle\Controller;
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

// ...

class ArticleController extends AbstractController
{
    public function recentArticlesAction($max = 3)
    {
        // выполните вызов к DB или другую логику,
        // чтобы получить "$max" самых последних статей
        $articles = [
           ['title' => 'https://google.com', 'slug' => 'Google'],
           ['title' => 'https://yandex.ru', 'slug' => 'Yandex'],
           ['title' => 'https://rambler.com', 'slug' => 'Rumbler'],
	];

        return $this->render(
            'article/recent_list.html.twig',
            array('articles' => $articles)
        );
    }
}