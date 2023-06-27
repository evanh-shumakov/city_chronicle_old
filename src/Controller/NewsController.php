<?php

namespace App\Controller;

use App\Entity\News;
use App\Repository\NewsRepository;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
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

    #[Route('/news/{id}', 'news_detail')]
    public function showDetail(#[MapEntity] News $news): Response
    {
        return $this->render('news/detail.html.twig', ['news' => $news]);
    }
}
