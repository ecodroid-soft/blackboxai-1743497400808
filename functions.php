<?php
require_once 'config.php';

// Database connection function
function getDBConnection() {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $pdo = new PDO($dsn, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch(PDOException $e) {
        error_log("Connection failed: " . $e->getMessage());
        return false;
    }
}

// Input sanitization
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Check if user is logged in
function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
}

// Redirect function
function redirect($url) {
    header("Location: $url");
    exit();
}

// Get latest results
function getLatestResults($limit = 10) {
    try {
        $pdo = getDBConnection();
        if (!$pdo) {
            throw new Exception("Database connection failed");
        }
        
        $stmt = $pdo->prepare("SELECT * FROM satta_results ORDER BY date DESC LIMIT :limit");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(Exception $e) {
        error_log("Error fetching results: " . $e->getMessage());
        return false;
    }
}

// Format date
function formatDate($date) {
    return date('d M Y', strtotime($date));
}

// Generate CSRF token
function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Verify CSRF token
function verifyCSRFToken($token) {
    if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
        return false;
    }
    return true;
}

// Check if admin credentials are valid
function checkAdminLogin($username, $password) {
    try {
        $pdo = getDBConnection();
        if (!$pdo) {
            throw new Exception("Database connection failed");
        }
        
        $stmt = $pdo->prepare("SELECT id, password FROM admins WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if (password_verify($password, $row['password'])) {
                return $row['id'];
            }
        }
        return false;
    } catch(Exception $e) {
        error_log("Login error: " . $e->getMessage());
        return false;
    }
}
?>