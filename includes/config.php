<?php
session_start();

define('DATA_DIR', __DIR__ . '/../data');
define('TICKETS_FILE', DATA_DIR . '/tickets.json');

if (!is_dir(DATA_DIR)) mkdir(DATA_DIR, 0755, true);
if (!file_exists(TICKETS_FILE)) file_put_contents(TICKETS_FILE, json_encode([]));

require_once __DIR__ . '/../vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../templates');
$twig = new \Twig\Environment($loader, ['cache' => false]);