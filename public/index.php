<?php

require_once '../vendor/autoload.php';

echo "Hello Motherfunction!";

$database = new medoo([
    'database_type' => 'sqlite',
    'database_file' => '../storage/database.db'
]);

dump($database);