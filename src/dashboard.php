<?php
require_once __DIR__ . '/../includes/auth.php';
requireAuth();

$tickets = getTickets();
$stats = [
    'total' => count($tickets),
    'open' => count(array_filter($tickets, fn($t) => $t['status'] === 'open')),
    'in_progress' => count(array_filter($tickets, fn($t) => $t['status'] === 'in_progress')),
    'closed' => count(array_filter($tickets, fn($t) => $t['status'] === 'closed'))
];

echo $twig->render('dashboard.twig', ['stats' => $stats]);