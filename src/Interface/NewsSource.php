<?php

namespace App\Interface;

interface NewsSource
{
    public function composeNewsTitle(): string;

    public function composeNewsPreview(): string;

    public function composeNewsContent(): string;

    public function getSourceUrl(): string;
}
