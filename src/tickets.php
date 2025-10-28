<?php
require_once __DIR__ . '/../includes/auth.php';
requireAuth();

$tickets = getTickets();
$errors = [];
$toast = null;
$editing = false;
$ticket = ['title' => '', 'description' => '', 'status' => 'open', 'priority' => 'medium'];

// Handle Edit
if (isset($_GET['edit'])) {
    $editing = true;
    $editId = $_GET['edit'];
    foreach ($tickets as $t) {
        if ($t['id'] === $editId) {
            $ticket = $t;
            break;
        }
    }
}

// Handle Form Submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'create' || $action === 'update') {
        $data = [
            'title' => trim($_POST['title'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'status' => $_POST['status'] ?? 'open',
            'priority' => $_POST['priority'] ?? 'medium'
        ];
        
        $errors = validateTicket($data);
        
        if (empty($errors)) {
            if ($action === 'update') {
                $ticketId = $_POST['ticket_id'] ?? '';
                updateTicket($ticketId, $data);
                $toast = ['type' => 'success', 'message' => 'Ticket updated successfully!'];
            } else {
                addTicket($data);
                $toast = ['type' => 'success', 'message' => 'Ticket created successfully!'];
            }
            header('Location: /tickets.php');
            exit;
        } else {
            $ticket = $data;
            $editing = ($action === 'update');
        }
    }
    
    if ($action === 'delete') {
        $ticketId = $_POST['ticket_id'] ?? '';
        deleteTicket($ticketId);
        header('Location: /tickets.php');
        exit;
    }
}

$tickets = getTickets();

echo $twig->render('tickets.twig', [
    'tickets' => $tickets,
    'errors' => $errors,
    'toast' => $toast,
    'editing' => $editing,
    'ticket' => $ticket
]);