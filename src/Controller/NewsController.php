<?php

namespace App\Controller;

use App\Repository\NewsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewsController extends AbstractController
{
    #[Route('/', name: 'news_list')]
    public function showList(NewsRepository $newsRepository): Response
    {
        return $this->render('news/list.html.twig', [
            'controller_name' => 'NewsController',
            'newsList' => $newsRepository->findAll(),
        ]);
    }

    #[Route('/news/{newsId}', 'news_detail')]
    public function showDetail(
        NewsRepository $newsRepository,
        int $newsId,
    ): Response
    {
        return $this->render('news/detail.html.twig', [
            'news' => $newsRepository->find($newsId),
        ]);
    }
}
