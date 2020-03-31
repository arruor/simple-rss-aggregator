<?php

namespace App\Service;

interface FeedReaderServiceInterface
{
    public function parseFeed(string $url);
}
