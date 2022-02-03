<?php

Kirby::plugin('doldenroller/k3-translation-status', [
  'sections' => [
    'translationstatus' => require __DIR__ . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'translationStatus.php'
  ]
]);
