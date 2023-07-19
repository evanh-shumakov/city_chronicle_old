<?php

namespace App\Factory;

use App\Entity\News;
use App\Interface\NewsSource;

class NewsFactory
{
    static public function makeNews(NewsSource $source): News
    {
        $news = new News();
        $news->setDateTime(new \DateTime());
        $news->setTitle($source->composeNewsTitle());
        $news->setPreview($source->composeNewsPreview());
        $news->setContent($source->composeNewsContent());
        $news->setSourceUrl($source->getSourceUrl());
        return $news;
    }
}
