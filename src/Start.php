<?php
    if (!defined("__FIREGORE__")) define("__FIREGORE__", realpath(__DIR__.DIRECTORY_SEPARATOR."..").DIRECTORY_SEPARATOR);
    $dotenv = Dotenv\Dotenv::createImmutable(__FIREGORE__);
    $dotenv->load();