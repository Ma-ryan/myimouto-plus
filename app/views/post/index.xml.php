<?php
$dom = new DOMDocument('1.0', 'UTF-8');
$root = $dom->appendChild($dom->createElement('posts'));
$root->setAttribute('count', $this->posts->totalRows());
$root->setAttribute('offset', ($this->posts->currentPage() - 1) * $this->posts->perPage());

foreach ($this->posts as $post)
{
    $pel = $root->appendChild($dom->createElement('post'));

    foreach ($post->api_attributes() as $key => $value)
    {
        if (!isset($value) || is_array($value)) { continue; }
        if (is_bool($value)) { $value = $value ? 'true' : 'false'; }
        $pel->setAttribute($key, strval($value));
    }
}

echo($dom->saveXML());
