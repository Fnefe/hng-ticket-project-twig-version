<?php
require_once __DIR__ . '/../includes/auth.php';

if (isAuthenticated()) {
    header('Location: /dashboard.php');
    exit;
}

$errors = [];
$email = '';
$toast = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (empty($email)) $errors['email'] = 'Email is required';
    if (empty($password)) $errors['password'] = 'Password is required';
    
    if (empty($errors)) {
        $result = login($email, $password);
        if ($result['success']) {
            header('Location: /dashboard.php');
            exit;
        } else {
            $toast = ['type' => 'error', 'message' => $result['error']];
        }
    }
}

echo $twig->render('login.twig', [
    'errors' => $errors,
    'email' => $email,
    'toast' => $toast
]);