<?php
$dom = new DOMDocument('1.0', 'UTF-8');
$root = $dom->appendChild($dom->createElement('tags'));

foreach ($this->tags as $parent => $related)
{
    $pel = $root->appendChild($dom->createElement('tag'));
    $pel->setAttribute('name', $parent);

    foreach ($related as $tag)
    {
        $rel = $pel->appendChild($dom->createElement('tag'));
        $rel->setAttribute('name', $tag[0]);
        $rel->setAttribute('count', $tag[1]);
    }
}

echo($dom->saveXML());
