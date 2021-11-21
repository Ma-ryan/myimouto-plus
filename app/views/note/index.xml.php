<?php
$dom = new DOMDocument('1.0', 'UTF-8');
$root = $dom->appendChild($dom->createElement('notes'));

foreach ($this->notes as $note)
{
    $el = $root->appendChild($dom->createElement('note'));

    foreach ($note->asJson() as $key => $value)
    {
        if (!isset($value) || is_array($value)) { continue; }
        if (is_bool($value)) { $value = $value ? 'true' : 'false'; }
        $el->setAttribute($key, strval($value));
    }
}

echo($dom->saveXML());
