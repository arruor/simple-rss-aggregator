<?php

namespace App\Service;

use App\Service\FeedReader\Atom;
use App\Service\FeedReader\Exception\Common;
use App\Service\FeedReader\Rss;
use Throwable;

class FeedReaderService implements FeedReaderServiceInterface
{
    private $parser;

    /**
     * FeedReaderService constructor.
     * @param string $parses
     * @throws Throwable
     */
    public function __construct(string $parses = 'rss')
    {
        switch ($parses) {
            case 'rss':
                $this->setParser(new Rss());
                break;
            case 'atom':
                $this->setParser(new Atom());
                break;
            default:
                throw new Common('Unknown parser type.');
                break;
        }
    }

    public function parseFeed(string $url)
    {
        return $this->getParser()->load($url);
    }

    /**
     * @return mixed
     */
    public function getParser()
    {
        return $this->parser;
    }

    /**
     * @param mixed $parser
     */
    public function setParser($parser): void
    {
        $this->parser = $parser;
    }
}
