<?php
require_once 'includes/auth_check.php';
require_once 'config/constants.php';

$result = null;
$error = null;
$uploaded_image_data = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['plant_image'])) {
    $api_url = DJANGO_API_URL . 'ai/identify/';
    $image_file = $_FILES['plant_image'];
    
    // Validate file
    $allowed_types = ['image/jpeg', 'image/png'];
    if (!in_array($image_file['type'], $allowed_types)) {
        $error = 'Only JPG and PNG images are allowed.';
    } elseif ($image_file['size'] > 5000000) { // 5MB limit
        $error = 'Image size must be less than 5MB.';
    } elseif ($image_file['error'] !== UPLOAD_ERR_OK) {
        $error = 'File upload error. Please try again.';
    } else {
try {
    $boundary = '----'.md5(mt_rand().microtime());
    $header = "Content-Type: multipart/form-data; boundary=$boundary";
    
    // Prepare the file content
    $file_content = file_get_contents($image_file['tmp_name']);
    $filename = $image_file['name'];
    
    // Build the multipart body
    $body = "--$boundary\r\n";
    $body .= "Content-Disposition: form-data; name=\"image\"; filename=\"$filename\"\r\n";
    $body .= "Content-Type: {$image_file['type']}\r\n\r\n";
    $body .= $file_content."\r\n";
    $body .= "--$boundary--\r\n";
    
    $options = [
        'http' => [
            'header' => $header,
            'method' => 'POST',
            'content' => $body,
            'timeout' => 30
        ]
    ];
    
    $context = stream_context_create($options);
    $response = file_get_contents($api_url, false, $context);
    
    if ($response === false) {
        throw new Exception('API request failed');
    }
    
    $result = json_decode($response, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Invalid JSON response');
    }
    
    if (!$result['success']) {
        throw new Exception($result['error'] ?? 'API request failed');
    }
    
    // Prepare image for display
    $uploaded_image_data = 'data:'.$image_file['type'].';base64,'.base64_encode($file_content);
    
} catch (Exception $e) {
    $error = 'Error: ' . $e->getMessage();
    error_log($e->getMessage());
}
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plant ID - <?= htmlspecialchars(SITE_NAME) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #28a745;
            --secondary-color: #6c757d;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
        }
        
        body {
            background-color: #f5f5f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .card {
            border-radius: 12px;
            overflow: hidden;
            border: none;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
        }
        
        .card-header {
            background-color: var(--primary-color);
            color: white;
            font-weight: 600;
            border-bottom: none;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 10px 20px;
            font-weight: 500;
            letter-spacing: 0.5px;
            transition: all 0.3s;
        }
        
        .btn-primary:hover {
            background-color: #218838;
            border-color: #1e7e34;
            transform: translateY(-2px);
        }
        
        .prediction-card {
            transition: all 0.3s;
            cursor: pointer;
            border-radius: 8px !important;
            margin-bottom: 12px;
            border: 1px solid #e9ecef;
        }
        
        .prediction-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            border-color: var(--primary-color);
        }
        
        .confidence-bar {
            height: 8px;
            background: #e9ecef;
            border-radius: 4px;
            overflow: hidden;
            margin-top: 8px;
        }
        
        .confidence-level {
            height: 100%;
            background: linear-gradient(90deg, #4CAF50, #8BC34A);
            transition: width 0.8s ease;
            border-radius: 4px;
        }
        
        .top-match-card {
            border: 2px solid var(--primary-color);
            background-color: rgba(40, 167, 69, 0.05);
        }
        
        .top-match-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: var(--primary-color);
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        
        #uploadedImage {
            max-height: 300px;
            object-fit: contain;
            background-color: #f8f9fa;
            padding: 10px;
        }
        
        .form-control {
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid #ced4da;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(40, 167, 69, 0.25);
        }
        
        .alert-info {
            background-color: #e7f8ff;
            border-color: #b8e6ff;
            color: #00688b;
        }
        
        .loading-spinner {
            display: none;
            color: var(--primary-color);
        }
        
        .section-title {
            position: relative;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        
        .section-title:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 3px;
            background-color: var(--primary-color);
        }
        
        .match-badge {
            background-color: #e3f2fd;
            color: #1976d2;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <?php include 'includes/navigation.php'; ?>
    
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header text-center py-3">
                        <h3><i class="fas fa-seedling me-2"></i> Plant Identification</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" enctype="multipart/form-data" class="mb-4" id="plantForm">
                            <div class="mb-4">
                                <label for="plant_image" class="form-label fw-bold">Upload Plant Image</label>
                                <div class="input-group">
                                    <input type="file" class="form-control" id="plant_image" name="plant_image" accept="image/jpeg,image/png" required>
                                    <button class="btn btn-outline-secondary" type="button" id="cameraBtn">
                                        <i class="fas fa-camera"></i>
                                    </button>
                                </div>
                                <div class="form-text text-muted">Supported formats: JPG, PNG (Max 5MB)</div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 py-2" id="submitBtn">
                                <span id="submitText"><i class="fas fa-search me-2"></i>Identify Plant</span>
                                <span id="spinner" class="spinner-border spinner-border-sm ms-2 d-none" role="status"></span>
                            </button>
                        </form>
                        
                        <?php if ($error): ?>
                            <div class="alert alert-danger alert-dismissible fade show">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <?= htmlspecialchars($error) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                <?php if (strpos($error, 'HTTP 500') !== false): ?>
                                    <div class="mt-2">Please try again later or contact support.</div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <?php if ($result && $result['success']): ?>
                    <div class="card mb-4">
                        <div class="card-header bg-white">
                            <h4 class="mb-0"><i class="fas fa-clipboard-list me-2"></i>Identification Results</h4>
                        </div>
                        <div class="card-body">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="card h-100 top-match-card">
                                        <div class="top-match-badge">
                                            <i class="fas fa-trophy me-1"></i> Top Match
                                        </div>
                                        <img src="<?= htmlspecialchars($uploaded_image_data) ?>" 
                                             class="card-img-top p-3" 
                                             alt="Uploaded plant"
                                             id="uploadedImage">
                                        <div class="card-body text-center pt-0">
                                            <h3 class="text-success fw-bold mt-3"><?= htmlspecialchars(ucfirst($result['top_suggestion'])) ?></h3>
                                            <div class="d-flex justify-content-center align-items-center mt-3">
                                                <div class="me-3">
                                                    <div class="text-muted small">Confidence</div>
                                                    <div class="h4 fw-bold text-primary"><?= number_format($result['predictions'][0]['confidence'] * 100, 1) ?>%</div>
                                                </div>
                                                <div>
                                                    <div class="text-muted small">Description</div>
                                                    <div class="text-secondary"><?= htmlspecialchars($result['predictions'][0]['description']) ?></div>
                                                </div>
                                            </div>
                                            <button class="btn btn-outline-primary mt-3" onclick="searchPlant('<?= htmlspecialchars($result['top_suggestion']) ?>')">
                                                <i class="fas fa-book me-1"></i> Learn More
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="h-100">
                                        <h5 class="section-title"><i class="fas fa-list-ol me-2"></i>Other Possible Matches</h5>
                                        <div class="list-group" id="predictionsList">
                                            <?php foreach ($result['predictions'] as $index => $prediction): ?>
                                                <?php if ($index > 0): ?>
                                                    <div class="list-group-item prediction-card"
                                                         onclick="searchPlant('<?= htmlspecialchars($prediction['label']) ?>')">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <div>
                                                                <h6 class="mb-1 fw-bold"><?= htmlspecialchars(ucfirst($prediction['label'])) ?></h6>
                                                                <p class="mb-1 small text-muted"><?= htmlspecialchars($prediction['description']) ?></p>
                                                            </div>
                                                            <div class="text-end">
                                                                <span class="badge bg-primary rounded-pill"><?= number_format($prediction['confidence'] * 100, 1) ?>%</span>
                                                            </div>
                                                        </div>
                                                        <div class="confidence-bar">
                                                            <div class="confidence-level" 
                                                                 style="width: <?= $prediction['confidence'] * 100 ?>%"
                                                                 data-confidence="<?= $prediction['confidence'] * 100 ?>">
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <?php if (!empty($result['matches'])): ?>
                                <div class="mt-5">
                                    <h5 class="section-title"><i class="fas fa-database me-2"></i>Database Matches</h5>
                                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4" id="plantMatches">
                                        <?php foreach ($result['matches'] as $plant): ?>
                                            <div class="col">
                                                <div class="card h-100 plant-match-card">
                                                    <?php if (!empty($plant['image_filename'])): ?>
                                                        <img src="<?= htmlspecialchars(DJANGO_MEDIA_URL . 'plants/' . basename($plant['image_filename'])) ?>" 
                                                             class="card-img-top" 
                                                             alt="<?= htmlspecialchars($plant['plant_name']) ?>"
                                                             style="height: 180px; object-fit: cover;">
                                                    <?php endif; ?>
                                                    <div class="card-body">
                                                        <h6 class="card-title fw-bold"><?= htmlspecialchars($plant['plant_name']) ?></h6>
                                                        <p class="card-text small text-muted mb-2"><?= htmlspecialchars($plant['type']) ?></p>
                                                        <?php if (!empty($plant['uses'])): ?>
                                                            <div class="mb-2">
                                                                <?php foreach (explode(',', $plant['uses']) as $use): ?>
                                                                    <span class="badge match-badge me-1 mb-1"><?= htmlspecialchars(trim($use)) ?></span>
                                                                <?php endforeach; ?>
                                                            </div>
                                                        <?php endif; ?>
                                                        <div class="d-grid">
                                                            <a href="search.php?q=<?= urlencode($plant['plant_name']) ?>" 
                                                               class="btn btn-sm btn-outline-primary">
                                                                <i class="fas fa-info-circle me-1"></i> View Details
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-info mt-5">
                                    <i class="fas fa-info-circle me-2"></i>
                                    No exact matches found in our database. Try searching for 
                                    "<strong><?= htmlspecialchars($result['top_suggestion']) ?></strong>" to learn more.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Form submission handler
        document.getElementById('plantForm').addEventListener('submit', function() {
            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            const spinner = document.getElementById('spinner');
            
            submitBtn.disabled = true;
            submitText.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing';
            spinner.classList.remove('d-none');
        });

        // Camera button functionality
        document.getElementById('cameraBtn').addEventListener('click', function() {
            alert('Camera functionality would be implemented here');
            // Actual implementation would use the MediaDevices API
        });

        function searchPlant(plantName) {
            window.location.href = `search.php?q=${encodeURIComponent(plantName)}`;
        }

        // Animate elements on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Animate confidence bars
            const confidenceBars = document.querySelectorAll('.confidence-level');
            confidenceBars.forEach(bar => {
                const width = bar.getAttribute('data-confidence');
                bar.style.width = '0';
                setTimeout(() => {
                    bar.style.width = width + '%';
                }, 300);
            });
            
            // Add fade-in animation to cards
            const cards = document.querySelectorAll('.card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 100 * index);
            });
        });
    </script>
</body>
</html>