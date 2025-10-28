<?php
require_once __DIR__ . '/../includes/auth.php';

if (isAuthenticated()) {
    header('Location: /dashboard.php');
    exit;
}

$errors = [];
$name = '';
$email = '';
$toast = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    
    if (empty($name)) $errors['name'] = 'Name is required';
    if (empty($email)) $errors['email'] = 'Email is required';
    if (empty($password)) $errors['password'] = 'Password is required';
    if ($password !== $confirm) $errors['confirm_password'] = 'Passwords do not match';
    
    if (empty($errors)) {
        $result = signup($email, $password, $name);
        if ($result['success']) {
            header('Location: /dashboard.php');
            exit;
        } else {
            $toast = ['type' => 'error', 'message' => $result['error']];
        }
    }
}

echo $twig->render('signup.twig', [
    'errors' => $errors,
    'name' => $name,
    'email' => $email,
    'toast' => $toast
]);