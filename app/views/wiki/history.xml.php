<?php
$dom = new DOMDocument('1.0', 'UTF-8');
$root = $dom->appendChild($dom->createElement('wiki_pages'));

foreach ($this->wiki_pages as $page)
{
    $pel = $root->appendChild($dom->createElement('page'));
    $pel->setAttribute('id',            $page->id           );
    $pel->setAttribute('created_at',    $page->created_at   );
    $pel->setAttribute('updated_at',    $page->updated_at   );
    $pel->setAttribute('title',         $page->title        );
    $pel->setAttribute('body',          $page->body         );
    $pel->setAttribute('updater_id',    $page->user_id      );
    $pel->setAttribute('locked',        $page->is_locked    );
    $pel->setAttribute('version',       $page->version      );
}

echo($dom->saveXML());
