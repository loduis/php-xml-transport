<?php

namespace XML\Transport\Response;

use DOMXPath;
use DOMDocument;
use SimpleXMLElement;

class Finder
{
    private $xpath;

    private $namespace;

    public function __construct($document, $namespace = 'x')
    {
        if ($document instanceof SimpleXMLElement) {
            $document = $document->asXML();
        }
        $doc = new DOMDocument;
        $doc->loadXML($document);

        $this->xpath = new DOMXPath($doc);
        $this->xpath->registerNamespace($namespace, $doc->documentElement->namespaceURI);
        $this->namespace = $namespace;
    }

    public function getValue($tagName)
    {
        $nodeList = $this->xpath->query($this->namespace . ':' . $tagName);

        return $nodeList[0]->nodeValue;
    }
}
