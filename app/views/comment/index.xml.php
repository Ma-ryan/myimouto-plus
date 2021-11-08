<?php
$dom = new DOMDocument('1.0', 'UTF-8');
$root = $dom->appendChild($dom->createElement('comments'));
$root->setAttribute('count', $this->comments->totalRows());
$root->setAttribute('offset', ($this->comments->currentPage() - 1) * $this->comments->perPage());

foreach ($this->comments as $comment)
{
    $el = $root->appendChild($dom->createElement('comment'));

    foreach ($comment->api_attributes() as $key => $value)
    {
        if (!isset($value)) { continue; }
        if (is_bool($value)) { $value = $value ? 'true' : 'false'; }
        $el->setAttribute($key, strval($value));
    }
}

echo($dom->saveXML());
