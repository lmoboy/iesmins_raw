<?php
session_start();
require_once 'Database.php';

function debug_log($message, $type = 'info') {
    if (!DEBUG_AUTH) return;
    
    $log_message = date('[Y-m-d H:i:s]') . " [AUTH] [{$type}] {$message}\n";
    error_log($log_message, 3, DEBUG_LOG_FILE);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    debug_log('Processing login attempt');
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        debug_log('Login attempt failed: Empty username or password', 'warning');
        $_SESSION['error'] = "Please fill in all fields.";
        header('Location: /authentification/login');
        exit();
    }

    debug_log("Attempting authentication for user: {$email}");
    $database = new Database();
    $db = $database->connect();

    $query = "SELECT * FROM users WHERE email = :email";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        debug_log("Authentication successful for user: {$email}");
        // Store only necessary user data in session
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'role' => $user['role'] ?? 'user'
        ];
        
        // Regenerate session ID for security
        session_regenerate_id(true);
        debug_log("Session regenerated for user: {$email}");
        
        header('Location: /');
        exit();
    } else {
        debug_log("Authentication failed for user: {$email}", 'warning');
        $_SESSION['error'] = "Invalid username or password.";
        header('Location: /authentification/login');
        exit();
    }
}