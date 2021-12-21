<?php
$dom = new DOMDocument('1.0', 'UTF-8');
$root = $dom->appendChild($dom->createElement('artists'));
$root->setAttribute('count', $this->artists->totalRows());
$root->setAttribute('offset', ($this->artists->currentPage() - 1) * $this->artists->perPage());

foreach ($this->artists as $artist)
{
    $ael = $root->appendChild($dom->createElement('artist'));
    $attrs = $artist->api_attributes();

    $ael->setAttribute('id', strval($attrs['id']));
    $ael->setAttribute('name', strval($attrs['name']));
    $ael->setAttribute('alias_id', strval($attrs['alias_id']));
    $ael->setAttribute('group_id', strval($attrs['group_id']));
    $ael->setAttribute('urls', implode(' ', $attrs['urls']));

}

echo($dom->saveXML());
