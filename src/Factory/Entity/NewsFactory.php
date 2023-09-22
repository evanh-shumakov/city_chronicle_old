<?php

namespace App\Factory\Entity;

use App\Entity\News;
use App\Interface\NewsSource;

final class NewsFactory
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
