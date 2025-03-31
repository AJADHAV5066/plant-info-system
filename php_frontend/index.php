<?php
require_once 'includes/auth_check.php';
require_once 'config/constants.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars(SITE_NAME) ?></title>
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
        
        .navbar {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            background-color: white;
        }
        
        .container {
            flex: 1;
            padding-top: 2rem;
            padding-bottom: 2rem;
        }
        
        .hero-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .hero-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
        }
        
        .card-title {
            font-weight: 600;
            color: var(--dark-color);
        }
        
        .search-container {
            position: relative;
            max-width: 600px;
            margin: 0 auto 2rem;
        }
        
        .search-input {
            padding: 12px 20px;
            border-radius: 50px;
            border: 2px solid #e9ecef;
            font-size: 1rem;
            transition: all 0.3s;
        }
        
        .search-input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.25);
        }
        
        .search-btn {
            position: absolute;
            right: 5px;
            top: 5px;
            border-radius: 50px;
            padding: 7px 20px;
            background-color: var(--primary-color);
            border: none;
            transition: all 0.3s;
        }
        
        .search-btn:hover {
            background-color: #218838;
            transform: translateY(-2px);
        }
        
        .classification-container {
            background-color: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            margin-top: 2rem;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .upload-area {
            border: 2px dashed #dee2e6;
            border-radius: 10px;
            padding: 2rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            margin-bottom: 1.5rem;
            background-color: #f8f9fa;
        }
        
        .upload-area:hover {
            border-color: var(--primary-color);
            background-color: rgba(40, 167, 69, 0.05);
        }
        
        .upload-icon {
            font-size: 3rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
        
        .classify-btn {
            background-color: var(--primary-color);
            border: none;
            padding: 10px 25px;
            border-radius: 50px;
            font-weight: 500;
            letter-spacing: 0.5px;
            transition: all 0.3s;
            box-shadow: 0 4px 10px rgba(40, 167, 69, 0.3);
        }
        
        .classify-btn:hover {
            background-color: #218838;
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(40, 167, 69, 0.4);
        }
        
        .feature-cards {
            margin-top: 3rem;
        }
        
        .feature-card {
            border: none;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s;
            height: 100%;
            background-color: white;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .feature-icon {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }
        
        footer {
            background-color: var(--dark-color);
            color: white;
            padding: 2rem 0;
            margin-top: auto;
        }
        
        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fadein {
            animation: fadeIn 0.6s ease-out forwards;
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .hero-card {
                margin-top: 1rem;
            }
        }
    </style>
</head>
<body>
    <?php include 'includes/navigation.php'; ?>
    
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="hero-card animate-fadein">
                    <div class="card-body p-5">
                        <h1 class="card-title text-center mb-4">Discover the World of Plants</h1>
                        <p class="text-center text-muted mb-5">Identify plants, explore species, and learn about nature's wonders</p>
                        
                        <div class="search-container">
                            <form action="search.php" method="GET" class="position-relative">
                                <input type="text" name="q" class="form-control search-input" placeholder="Search for plants..." required>
                                <button type="submit" class="btn btn-primary search-btn">
                                    <i class="fas fa-search"></i> Search
                                </button>
                            </form>
                        </div>
                        
                        <div class="classification-container animate-fadein" style="animation-delay: 0.2s">
                            <h4 class="mb-4 text-center"><i class="fas fa-leaf me-2"></i> Plant Image Classification</h4>
                            <!-- <form action="classify.php" method="POST" enctype="multipart/form-data" id="plantForm">
    <div class="upload-area" id="uploadArea">
        <div class="upload-icon">
            <i class="fas fa-cloud-upload-alt"></i>
        </div>
        <h5>Drag & Drop your plant image here</h5>
        <p class="text-muted">or click to browse files</p>
        <input type="file" class="d-none" id="plant_image" name="plant_image" accept="image/jpeg,image/png" required>
        <div class="form-text">Supported formats: JPG, PNG (Max 5MB)</div>
    </div>

    <div class="text-center">
        <button type="submit" class="btn btn-primary" id="submitBtn">
            <i class="fas fa-microscope me-2"></i>
            <span id="submitText">Classify Plant</span>
            <span id="spinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
        </button>
    </div>
</form> -->
                            <form action="classify.php" method="POST" enctype="multipart/form-data" id="plantForm">
    <div class="mb-3">
        <label style="display: flex ; justify-content: center;" for="plant_image" class="form-label">Upload plant image</label>
        <input type="file" class="form-control" id="plant_image" name="plant_image" accept="image/jpeg,image/png" required>
        <div style="display: flex ; justify-content: center;" class="form-text">Supported formats: JPG, PNG (Max 5MB)</div>
    </div>
    <div class="text-center">
        <button type="submit" class="btn btn-primary" id="submitBtn" style="background: #28a745; border: none;">
            <i class="fas fa-microscope me-2"></i>
            <span id="submitText">Classify Plant</span>
            <span id="spinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
        </button>
    </div>
</form>
                        </div>
                        
                        <div class="row feature-cards">
                            <div class="col-md-4 mb-4">
                                <div class="feature-card p-4 text-center">
                                    <div class="feature-icon">
                                        <i class="fas fa-book-open"></i>
                                    </div>
                                    <h5>Plant Database</h5>
                                    <p class="text-muted">Explore our extensive collection of plant species and their characteristics.</p>
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <div class="feature-card p-4 text-center">
                                    <div class="feature-icon">
                                        <i class="fas fa-camera"></i>
                                    </div>
                                    <h5>Image Recognition</h5>
                                    <p class="text-muted">Upload a photo and our AI will identify the plant species for you.</p>
                                </div>
                            </div>
                            <div class="col-md-4 mb-4">
                                <div class="feature-card p-4 text-center">
                                    <div class="feature-icon">
                                        <i class="fas fa-chart-line"></i>
                                    </div>
                                    <h5>Growth Tracking</h5>
                                    <p class="text-muted">Monitor and track the growth of plants in your garden.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Drag and drop functionality
        const uploadArea = document.getElementById('uploadArea');
        const fileInput = document.getElementById('plant_image');
        
        uploadArea.addEventListener('click', () => fileInput.click());
        
        uploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            uploadArea.style.borderColor = '#28a745';
            uploadArea.style.backgroundColor = 'rgba(40, 167, 69, 0.1)';
        });
        
        uploadArea.addEventListener('dragleave', () => {
            uploadArea.style.borderColor = '#dee2e6';
            uploadArea.style.backgroundColor = '#f8f9fa';
        });
        
        uploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            uploadArea.style.borderColor = '#dee2e6';
            uploadArea.style.backgroundColor = '#f8f9fa';
            
            if (e.dataTransfer.files.length) {
                fileInput.files = e.dataTransfer.files;
                updateUploadArea();
            }
        });
        
        fileInput.addEventListener('change', updateUploadArea);
        
        function updateUploadArea() {
            if (fileInput.files.length) {
                const fileName = fileInput.files[0].name;
                uploadArea.innerHTML = `
                    <div class="upload-icon">
                        <i class="fas fa-check-circle text-success"></i>
                    </div>
                    <h5>${fileName}</h5>
                    <p class="text-muted">Ready to classify</p>
                    <small class="text-primary">Click to change</small>
                `;
            }
        }
        
        // Form submission loading state
        document.getElementById('plantForm').addEventListener('submit', function(e) {
    // Show loading state
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const spinner = document.getElementById('spinner');
    
    submitBtn.disabled = true;
    submitText.textContent = 'Processing...';
    spinner.classList.remove('d-none');
    
    // Form will submit normally to classify.php
});
        
        // Animation on scroll
        const animateElements = document.querySelectorAll('.animate-fadein');
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = 1;
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, { threshold: 0.1 });
        
        animateElements.forEach(el => {
            el.style.opacity = 0;
            el.style.transform = 'translateY(20px)';
            el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(el);
        });
    </script>
</body>
</html>