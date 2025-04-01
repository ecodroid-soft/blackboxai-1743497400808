<?php
require_once 'config.php';
require_once 'functions.php';

// Fetch latest results
$latestResults = getLatestResults(10);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        .navbar-brand {
            font-weight: 600;
            color: #2c3e50;
        }
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)),
                        url('https://source.unsplash.com/random?lottery') center/cover;
            height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            margin-bottom: 2rem;
        }
        .hero-section h1 {
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }
        .result-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .result-card:hover {
            transform: translateY(-5px);
        }
        .result-number {
            font-size: 24px;
            font-weight: 600;
            color: #2c3e50;
        }
        .result-date {
            color: #7f8c8d;
            font-size: 14px;
        }
        .footer {
            background: #2c3e50;
            color: white;
            padding: 2rem 0;
            margin-top: 3rem;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-trophy me-2"></i><?php echo SITE_NAME; ?>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#results">Results</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="admin/index.php">Admin Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-section">
        <div class="container">
            <h1 class="display-4">Welcome to <?php echo SITE_NAME; ?></h1>
            <p class="lead">Check the latest Satta results instantly</p>
        </div>
    </div>

    <!-- Results Section -->
    <section id="results" class="py-5">
        <div class="container">
            <h2 class="text-center mb-4">Latest Results</h2>
            <div class="row g-4">
                <?php if ($latestResults): ?>
                    <?php foreach ($latestResults as $result): ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="result-card p-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="result-date">
                                        <i class="far fa-calendar-alt me-2"></i>
                                        <?php echo formatDate($result['date']); ?>
                                    </span>
                                    <span class="badge bg-primary"><?php echo htmlspecialchars($result['game_type']); ?></span>
                                </div>
                                <div class="result-number text-center py-3">
                                    <?php echo htmlspecialchars($result['number']); ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center">
                        <div class="alert alert-info">
                            No results available at the moment.
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>About <?php echo SITE_NAME; ?></h5>
                    <p>Your trusted source for accurate and timely Satta results.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="index.php" class="text-white">Home</a></li>
                        <li><a href="#results" class="text-white">Results</a></li>
                        <li><a href="admin/index.php" class="text-white">Admin</a></li>
                    </ul>
                </div>
            </div>
            <hr class="mt-4">
            <div class="text-center">
                <p class="mb-0">&copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>