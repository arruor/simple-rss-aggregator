<?php

namespace App\Service\FeedReader;

use App\Service\FeedReader\Exception\ReadOnlyProperty;
use App\Service\FeedReader\Exception\Common;
use SimpleXMLElement;
use Throwable;

abstract class AbstractReader implements FeedReaderInterface
{
    /** @var string */
    private $cacheExpire;

    /** @var string */
    private $cacheDir;

    /** @var SimpleXMLElement */
    private $xml;

    public function __construct()
    {
        $this->setCacheExpire('1 day');
        $this->setCacheDir('/tmp');
    }

    /**
     * Returns property value. Do not call directly.
     * @param  string  tag name
     * @return SimpleXMLElement
     */
    public function __get($name)
    {
        return $this->xml->{$name};
    }

    /**
     * Sets value of a property. Do not call directly.
     * @param $name
     * @param $value
     * @return void
     * @throws Throwable
     */
    public function __set($name, $value)
    {
        throw new ReadOnlyProperty("Cannot assign to a read-only property '$name'.");
    }

    /**
     * @param string $url
     * @param string|null $user
     * @param string|null $pass
     * @return $this|Rss
     * @throws Throwable
     */
    public function load(string $url, string $user = null, string $pass = null)
    {
        return $this->parse($this->loadXml($url, $user, $pass));
    }

    /**
     * Converts a SimpleXMLElement into an array.
     * @param SimpleXMLElement|null $xml
     * @return array|string
     */
    public function toArray(SimpleXMLElement $xml = null)
    {
        if ($xml === null) {
            $xml = $this->getXml();
        }

        if (!$xml->children()) {
            return (string) $xml;
        }

        $arr = [];
        foreach ($xml->children() as $tag => $child) {
            if (count($xml->$tag) === 1) {
                $arr[$tag] = $this->toArray($child);
            } else {
                $arr[$tag][] = $this->toArray($child);
            }
        }

        return $arr;
    }

    /**
     * @return string
     */
    public function getCacheExpire(): string
    {
        return $this->cacheExpire;
    }

    /**
     * @param string $cacheExpire
     * @return AbstractReader
     */
    public function setCacheExpire(string $cacheExpire): self
    {
        $this->cacheExpire = $cacheExpire;
        return $this;
    }

    /**
     * @return string
     */
    public function getCacheDir(): string
    {
        return $this->cacheDir;
    }

    /**
     * @param string $cacheDir
     * @return AbstractReader
     */
    public function setCacheDir(string $cacheDir): self
    {
        $this->cacheDir = $cacheDir;
        return $this;
    }

    /**
     * @return SimpleXMLElement
     */
    public function getXml(): SimpleXMLElement
    {
        return $this->xml;
    }

    /**
     * @param SimpleXMLElement $xml
     * @return AbstractReader
     */
    public function setXml(SimpleXMLElement $xml): self
    {
        $this->xml = $xml;
        return $this;
    }

    /**
     * Process HTTP request.
     * @param  string
     * @param  string
     * @param  string
     * @return string|false
     * @throws Throwable
     */
    protected function httpRequest($url, $user, $pass)
    {
        if (extension_loaded('curl')) {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            if ($user !== null || $pass !== null) {
                curl_setopt($curl, CURLOPT_USERPWD, "$user:$pass");
            }
            $user_agent = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.77 Safari/537.36";
            curl_setopt($curl, CURLOPT_USERAGENT, $user_agent); // some feeds require a user agent
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_TIMEOUT, 20);
            curl_setopt($curl, CURLOPT_ENCODING, '');
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); // no echo, just return result
            if (!ini_get('open_basedir')) {
                curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true); // sometime is useful :)
            }
            $result = curl_exec($curl);
            return curl_errno($curl) === 0 && curl_getinfo($curl, CURLINFO_HTTP_CODE) === 200
                ? $result
                : false;

        } else {
            $context = null;
            if ($user !== null && $pass !== null) {
                $options = [
                    'http' => [
                        'method' => 'GET',
                        'header' => 'Authorization: Basic ' . base64_encode($user . ':' . $pass) . "\r\n",
                    ],
                ];
                $context = stream_context_create($options);
            }

            return file_get_contents($url, false, $context);
        }
    }

    /**
     * Generates better accessible namespaced tags.
     * @param  SimpleXMLElement
     * @return void
     */
    protected function adjustNamespaces($el)
    {
        foreach ($el->getNamespaces(true) as $prefix => $ns) {
            $children = $el->children($ns);
            foreach ($children as $tag => $content) {
                $el->{$prefix . ':' . $tag} = $content;
            }
        }
    }

    /**
     * Load XML from cache or HTTP.
     * @param  string
     * @param  string
     * @param  string
     * @return SimpleXMLElement
     * @throws Throwable
     */
    protected function loadXml($url, $user, $pass)
    {
        $e = $this->getCacheExpire();
        $cacheFile = $this->getCacheDir() . '/feed.' . md5(serialize(func_get_args())) . '.xml';

        if ($this->getCacheExpire()
            && (time() - @filemtime($cacheFile) <= (is_string($e) ? strtotime($e) - time() : $e))
            && $data = @file_get_contents($cacheFile)
        ) {
            // ok
        } elseif ($data = trim(self::httpRequest($url, $user, $pass))) {
            if ($this->getCacheDir()) {
                file_put_contents($cacheFile, $data);
            }
        } else {
            throw new Common('Cannot load feed.');
        }

        return new SimpleXMLElement($data, LIBXML_NOWARNING | LIBXML_NOERROR);
    }
}
