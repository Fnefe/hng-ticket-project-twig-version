<?php
require_once __DIR__ . '/config.php';

function isAuthenticated() {
    return isset($_SESSION['ticketapp_session']);
}

function requireAuth() {
    if (!isAuthenticated()) {
        header('Location: /login.php');
        exit;
    }
}

function login($email, $password) {
    if ($email === 'user@test.com' && $password === 'password123') {
        $_SESSION['ticketapp_session'] = 'token-' . time();
        return ['success' => true];
    }
    return ['success' => false, 'error' => 'Invalid credentials'];
}

function signup($email, $password, $name) {
    if (empty($email) || empty($password) || empty($name)) {
        return ['success' => false, 'error' => 'All fields are required'];
    }
    if (strlen($password) < 6) {
        return ['success' => false, 'error' => 'Password must be at least 6 characters'];
    }
    $_SESSION['ticketapp_session'] = 'token-' . time();
    return ['success' => true];
}

function logout() {
    session_destroy();
    header('Location: /index.php');
    exit;
}

function getTickets() {
    return json_decode(file_get_contents(TICKETS_FILE), true) ?: [];
}

function saveTickets($tickets) {
    file_put_contents(TICKETS_FILE, json_encode($tickets, JSON_PRETTY_PRINT));
}

function addTicket($data) {
    $tickets = getTickets();
    $ticket = [
        'id' => uniqid(),
        'title' => $data['title'],
        'description' => $data['description'] ?? '',
        'status' => $data['status'],
        'priority' => $data['priority'] ?? 'medium',
        'createdAt' => date('Y-m-d H:i:s')
    ];
    $tickets[] = $ticket;
    saveTickets($tickets);
    return $ticket;
}

function updateTicket($id, $data) {
    $tickets = getTickets();
    foreach ($tickets as &$ticket) {
        if ($ticket['id'] === $id) {
            $ticket = array_merge($ticket, $data);
            saveTickets($tickets);
            return $ticket;
        }
    }
    return null;
}

function deleteTicket($id) {
    $tickets = array_filter(getTickets(), fn($t) => $t['id'] !== $id);
    saveTickets(array_values($tickets));
}

function validateTicket($data) {
    $errors = [];
    if (empty(trim($data['title']))) $errors['title'] = 'Title is required';
    if (empty($data['status'])) $errors['status'] = 'Status is required';
    if (!in_array($data['status'], ['open', 'in_progress', 'closed'])) {
        $errors['status'] = 'Invalid status';
    }
    if (!empty($data['description']) && strlen($data['description']) > 500) {
        $errors['description'] = 'Description too long';
    }
    return $errors;
}