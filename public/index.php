<?php

use App\Kernel;

include_once __DIR__ . '/../vendor/autoload.php';

session_start();

Kernel::getInstance()->configure()->run();
