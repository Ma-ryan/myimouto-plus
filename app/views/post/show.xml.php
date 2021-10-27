<?php
$dom = new DOMDocument('1.0', 'UTF-8');
$root = $dom->appendChild($dom->createElement('post'));

foreach ($this->post->api_attributes() as $key => $value)
{
    if (!isset($value)) { continue; }
    if (is_bool($value)) { $value = $value ? 'true' : 'false'; }
    $root->setAttribute($key, strval($value));
}

echo($dom->saveXML());
