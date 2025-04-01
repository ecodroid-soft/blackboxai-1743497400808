<?php
require_once '../config.php';
require_once '../functions.php';

// Check if admin is logged in
if (!isAdminLoggedIn()) {
    redirect('index.php');
}

// Handle form submission for new result
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $csrf_token = $_POST['csrf_token'] ?? '';
    
    if (!verifyCSRFToken($csrf_token)) {
        $message = 'Invalid request';
        $messageType = 'danger';
    } else {
        $date = sanitizeInput($_POST['date'] ?? '');
        $number = sanitizeInput($_POST['number'] ?? '');
        $game_type = sanitizeInput($_POST['game_type'] ?? '');

        try {
            $pdo = getDBConnection();
            $stmt = $pdo->prepare("INSERT INTO satta_results (date, number, game_type) VALUES (:date, :number, :game_type)");
            $stmt->execute([
                ':date' => $date,
                ':number' => $number,
                ':game_type' => $game_type
            ]);
            
            $message = 'Result added successfully!';
            $messageType = 'success';
        } catch(Exception $e) {
            $message = 'Error adding result';
            $messageType = 'danger';
            error_log("Error: " . $e->getMessage());
        }
    }
}

// Fetch latest results
$latestResults = getLatestResults(5);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - <?php echo SITE_NAME; ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        .sidebar {
            background: white;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            padding: 2rem 1rem;
            width: 250px;
        }
        .main-content {
            margin-left: 250px;
            padding: 2rem;
        }
        .sidebar-brand {
            font-weight: 600;
            font-size: 1.25rem;
            margin-bottom: 2rem;
            display: block;
            text-decoration: none;
            color: #2c3e50;
        }
        .nav-link {
            color: #2c3e50;
            padding: 0.75rem 1rem;
            margin-bottom: 0.5rem;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        .nav-link:hover, .nav-link.active {
            background: #f8f9fa;
            color: #764ba2;
        }
        .nav-link i {
            width: 20px;
            text-align: center;
            margin-right: 10px;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .card-header {
            background: white;
            border-bottom: 1px solid #eee;
            padding: 1.5rem;
            border-radius: 15px 15px 0 0 !important;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
        .btn-primary:hover {
            opacity: 0.9;
        }
        .table {
            margin-bottom: 0;
        }
        .table th {
            border-top: none;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <a href="dashboard.php" class="sidebar-brand">
            <i class="fas fa-trophy me-2"></i><?php echo SITE_NAME; ?>
        </a>
        <nav class="nav flex-column">
            <a href="dashboard.php" class="nav-link active">
                <i class="fas fa-tachometer-alt"></i>Dashboard
            </a>
            <a href="#" class="nav-link">
                <i class="fas fa-history"></i>Results History
            </a>
            <a href="#" class="nav-link">
                <i class="fas fa-cog"></i>Settings
            </a>
            <a href="logout.php" class="nav-link text-danger">
                <i class="fas fa-sign-out-alt"></i>Logout
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">Dashboard</h1>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addResultModal">
                    <i class="fas fa-plus me-2"></i>Add New Result
                </button>
            </div>

            <?php if ($message): ?>
                <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($message); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Latest Results Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Latest Results</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Game Type</th>
                                    <th>Number</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($latestResults): ?>
                                    <?php foreach ($latestResults as $result): ?>
                                        <tr>
                                            <td><?php echo formatDate($result['date']); ?></td>
                                            <td><?php echo htmlspecialchars($result['game_type']); ?></td>
                                            <td><?php echo htmlspecialchars($result['number']); ?></td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary me-2">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center">No results found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Result Modal -->
    <div class="modal fade" id="addResultModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Result</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="">
                    <div class="modal-body">
                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                        
                        <div class="mb-3">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" class="form-control" id="date" name="date" required>
                        </div>

                        <div class="mb-3">
                            <label for="game_type" class="form-label">Game Type</label>
                            <input type="text" class="form-control" id="game_type" name="game_type" required>
                        </div>

                        <div class="mb-3">
                            <label for="number" class="form-label">Number</label>
                            <input type="text" class="form-control" id="number" name="number" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Result</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>