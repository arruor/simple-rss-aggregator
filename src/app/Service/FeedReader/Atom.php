<?php

namespace App\Service\FeedReader;

use SimpleXMLElement;
use App\Service\FeedReader\Exception\Common;
use Throwable;

class Atom extends AbstractReader
{
    /**
     * @param SimpleXMLElement $xml
     * @return Atom
     * @throws Throwable
     */
    public function parse(SimpleXMLElement $xml)
    {
        if (!in_array('http://www.w3.org/2005/Atom', $xml->getDocNamespaces(), true)
            && !in_array('http://purl.org/atom/ns#', $xml->getDocNamespaces(), true)
        ) {
            throw new Common('Invalid feed.');
        }

        // generate 'timestamp' tag
        foreach ($xml->entry as $entry) {
            $entry->timestamp = strtotime($entry->updated);
        }
        $feed = new self;
        $feed->setXml($xml);

        return $feed;
    }
}
