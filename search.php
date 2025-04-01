<?php
require_once 'includes/auth_check.php';
require_once 'config/constants.php';

$search_term = isset($_GET['q']) ? trim($_GET['q']) : '';
$plants = [];
$error = null;

if (!empty($search_term)) {
    $api_url = DJANGO_API_URL . 'search/?q=' . urlencode($search_term);
    
    // First try using cURL if available
    if (function_exists('curl_init')) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);
        
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            $error = 'API request failed: ' . curl_error($ch);
        }
        curl_close($ch);
    } 
    // Fallback to file_get_contents if cURL not available
    elseif (ini_get('allow_url_fopen')) {
        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => "Accept: application/json\r\n"
            ]
        ]);
        
        $response = @file_get_contents($api_url, false, $context);
        if ($response === false) {
            $error = 'API request failed: ' . error_get_last()['message'];
        }
    } else {
        $error = 'Neither cURL nor allow_url_fopen is available on this server.';
    }
    
    if (!$error && isset($response)) {
        $plants = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $error = 'Failed to decode API response: ' . json_last_error_msg();
            $plants = [];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - <?= htmlspecialchars(SITE_NAME) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #28a745;
            --secondary-color: #6c757d;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
            --accent-color: #ffc107;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
            color: #333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .search-header {
            background: linear-gradient(135deg, rgba(40, 167, 69, 0.1) 0%, rgba(255, 255, 255, 1) 100%);
            padding: 2rem 0;
            margin-bottom: 2rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .search-title {
            font-weight: 600;
            color: var(--dark-color);
            position: relative;
            display: inline-block;
        }
        
        .search-title:after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 50px;
            height: 3px;
            background-color: var(--primary-color);
        }
        
        .plant-card {
            border: none;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            height: 100%;
            background-color: white;
            margin-bottom: 1.5rem;
        }
        
        .plant-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .plant-img {
            height: 200px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        
        .plant-card:hover .plant-img {
            transform: scale(1.05);
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        .plant-name {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
        }
        
        .plant-type {
            display: inline-block;
            background-color: rgba(40, 167, 69, 0.1);
            color: var(--primary-color);
            padding: 0.25rem 0.75rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 500;
            margin-bottom: 1rem;
        }
        
        .plant-details {
            margin-top: 1rem;
        }
        
        .detail-item {
            display: flex;
            margin-bottom: 0.5rem;
            align-items: flex-start;
        }
        
        .detail-icon {
            color: var(--primary-color);
            margin-right: 0.75rem;
            margin-top: 0.2rem;
            flex-shrink: 0;
        }
        
        .detail-label {
            font-weight: 500;
            color: var(--dark-color);
            margin-right: 0.5rem;
        }
        
        .detail-value {
            color: var(--secondary-color);
        }
        
        .no-results {
            text-align: center;
            padding: 3rem;
        }
        
        .no-results-icon {
            font-size: 4rem;
            color: var(--secondary-color);
            opacity: 0.3;
            margin-bottom: 1rem;
        }
        
        .back-btn {
            background-color: var(--primary-color);
            border: none;
            padding: 0.5rem 1.5rem;
            border-radius: 50px;
            font-weight: 500;
            transition: all 0.3s;
            box-shadow: 0 4px 10px rgba(40, 167, 69, 0.3);
            display: inline-flex;
            align-items: center;
        }
        
        .back-btn:hover {
            background-color: #218838;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(40, 167, 69, 0.4);
        }
        
        .back-btn i {
            margin-right: 0.5rem;
        }
        
        /* Animation */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-card {
            animation: fadeInUp 0.6s ease-out forwards;
            opacity: 0;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .plant-img {
                height: 150px;
            }
            
            .card-body {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <?php include 'includes/navigation.php'; ?>
    
    <div class="search-header">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1 class="search-title">Search Results for "<?= htmlspecialchars($search_term) ?>"</h1>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container">
        <div class="row">
            <div class="col-12">
                <?php if ($error): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php elseif (empty($plants)): ?>
                    <div class="no-results">
                        <div class="no-results-icon">
                            <i class="fas fa-leaf"></i>
                        </div>
                        <h3>No plants found</h3>
                        <p class="text-muted">We couldn't find any plants matching "<?= htmlspecialchars($search_term) ?>"</p>
                        <a href="index.php" class="btn back-btn">
                            <i class="fas fa-arrow-left"></i> Back to Search
                        </a>
                    </div>
                <?php else: ?>
                    <div class="row">
                        <?php foreach ($plants as $index => $plant): ?>
                            <div class="col-md-6 col-lg-4 animate-card" style="animation-delay: <?= $index * 0.1 ?>s">
                                <div class="plant-card">
                                    <?php if (!empty($plant['image_filename'])): ?>
                                        <img src="<?= htmlspecialchars(DJANGO_MEDIA_URL) ?>plants/<?= htmlspecialchars(basename($plant['image_filename'])) ?>" 
                                             class="plant-img w-100" 
                                             alt="<?= htmlspecialchars($plant['plant_name']) ?>">
                                    <?php else: ?>
                                        <div class="plant-img w-100 bg-light d-flex align-items-center justify-content-center">
                                            <i class="fas fa-leaf fa-3x text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                    <div class="card-body">
                                        <h3 class="plant-name"><?= htmlspecialchars($plant['plant_name']) ?></h3>
                                        <span class="plant-type"><?= htmlspecialchars($plant['type']) ?></span>
                                        
                                        <div class="plant-details">
                                            <div class="detail-item">
                                                <span class="detail-icon"><i class="fas fa-leaf"></i></span>
                                                <div>
                                                    <span class="detail-label">Leaves:</span>
                                                    <span class="detail-value"><?= htmlspecialchars($plant['leaves']) ?></span>
                                                </div>
                                            </div>
                                            
                                            <div class="detail-item">
                                                <span class="detail-icon"><i class="fas fa-spa"></i></span>
                                                <div>
                                                    <span class="detail-label">Flowers:</span>
                                                    <span class="detail-value"><?= htmlspecialchars($plant['flowers']) ?></span>
                                                </div>
                                            </div>
                                            
                                            <div class="detail-item">
                                                <span class="detail-icon"><i class="fas fa-apple-alt"></i></span>
                                                <div>
                                                    <span class="detail-label">Fruits:</span>
                                                    <span class="detail-value"><?= htmlspecialchars($plant['fruits']) ?></span>
                                                </div>
                                            </div>
                                            
                                            <div class="detail-item">
                                                <span class="detail-icon"><i class="fas fa-chart-line"></i></span>
                                                <div>
                                                    <span class="detail-label">Growth:</span>
                                                    <span class="detail-value"><?= htmlspecialchars($plant['growth']) ?></span>
                                                </div>
                                            </div>
                                            
                                            <div class="detail-item">
                                                <span class="detail-icon"><i class="fas fa-utensils"></i></span>
                                                <div>
                                                    <span class="detail-label">Uses:</span>
                                                    <span class="detail-value"><?= htmlspecialchars($plant['uses']) ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="text-center mt-4">
                        <a href="index.php" class="btn back-btn">
                            <i class="fas fa-arrow-left"></i> Back to Search
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Animation on scroll
        const animateElements = document.querySelectorAll('.animate-card');
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = 1;
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, { threshold: 0.1 });
        
        animateElements.forEach(el => {
            observer.observe(el);
        });
    </script>
</body>
</html>
