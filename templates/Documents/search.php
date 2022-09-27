<?php

foreach ($documents as $document) {
    echo $this->Html->link("Document $document->documentId", [
        'action' => 'view',
        h($document->documentId)
    ]);
    echo "<br/>";
}