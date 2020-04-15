<?php declare(strict_types=1);

use Przeslijmi\Silogger\Silogger;

Silogger::declare(
  'default',
  [
    'cli' => [
      'levels' => [
        Silogger::DEBUG
      ]
    ]
  ]
);
