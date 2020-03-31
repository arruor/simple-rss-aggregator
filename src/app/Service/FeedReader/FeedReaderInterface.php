<?php

namespace App\Service\FeedReader;

use SimpleXMLElement;

interface FeedReaderInterface
{
    public function load(string $url, string $user = null, string $pass = null);
    public function parse(SimpleXMLElement $xml);
}
