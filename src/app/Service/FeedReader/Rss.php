<?php

namespace App\Service\FeedReader;

use SimpleXMLElement;
use App\Service\FeedReader\Exception\Common;
use Throwable;

class Rss extends AbstractReader
{
    /**
     * @param SimpleXMLElement $xml
     * @return Rss
     * @throws Throwable
     */
    public function parse(SimpleXMLElement $xml): self
    {
        if (!$xml->channel) {
            throw new Common('Invalid feed.');
        }

        $this->adjustNamespaces($xml);

        foreach ($xml->channel->item as $item) {
            // converts namespaces to dotted tags
            $this->adjustNamespaces($item);

            // generate 'timestamp' tag
            if (isset($item->{'dc:date'})) {
                $item->timestamp = strtotime($item->{'dc:date'});
            } elseif (isset($item->pubDate)) {
                $item->timestamp = strtotime($item->pubDate);
            }
        }
        $feed = new self;
        $feed->setXml($xml->channel);

        return $feed;
    }
}
