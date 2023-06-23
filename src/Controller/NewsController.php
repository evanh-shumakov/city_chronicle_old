<?php

namespace App\Controller;

use App\Repository\NewsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewsController extends AbstractController
{
    #[Route('/', name: 'news_list')]
    public function index(NewsRepository $newsRepository): Response
    {
        return $this->render('news/index.html.twig', [
            'controller_name' => 'NewsController',
            'newsList' => $newsRepository->findAll(),
        ]);
    }

    #[Route('/news/{newsId}')]
    public function show(
        NewsRepository $newsRepository,
        int $newsId,
    ): Response
    {
        return $this->render('news/show.html.twig', [
            'news' => $newsRepository->find($newsId),
        ]);
    }
}
