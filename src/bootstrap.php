<?php

declare(strict_types=1);

include PROJECT_ROOT . '/src/autoload.php';

$env = parse_ini_file(PROJECT_ROOT .  '/.env');

foreach ($env as $name => $value) {
    if (!array_key_exists($name, $_ENV)) {
        $_ENV[$name] = $value;
    }
}

session_start();
