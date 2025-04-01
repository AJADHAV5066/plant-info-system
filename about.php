<?php
require_once 'includes/auth_check.php'; // If needed
require_once 'config/constants.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - <?= htmlspecialchars(SITE_NAME) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            animation: fadeIn 1s ease-out forwards;
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
	

<div class="container mt-5">
    <div class="hero-card animate-fadein text-center p-5">
        <h1 class="card-title mb-4">About Us</h1>
        <p class="text-muted mb-4">Welcome to <strong><?= htmlspecialchars(SITE_NAME) ?></strong>, where innovation meets passion.</p>	

        <!-- Team Image with Padding -->
        <div class="text-center mb-5">
           <img src="images/Logo.jpeg" alt="Our Logo" class="img-fluid shadow" 
    width="250" height="250" style="border-radius: 10px; padding: 15px; background-color: #90EE90;">

        </div>

        <div class="row mt-4">
            <div class="col-md-8 offset-md-2">
                <h3 class="text-primary">Who Are We?</h3>
                <p class="lead">
                    We are <strong>Bhargav Phadke</strong> and <strong>Saloni Ohale</strong>, two passionate innovators working towards a groundbreaking vision. 
                    Our goal is to develop India’s largest database with hyper-local connectivity, making information more accessible and meaningful for everyone.
                </p>
            </div>
        </div>

        <!-- Mission -->
        <div class="row mt-5">
            <div class="col-md-6">
                <h4 class="text-success"><i class="fas fa-seedling"></i> Future Scope</h4>
                <p>
                    Our vision extends beyond a database—we plan to introduce NATIVE PLANTS and train our own AI model someday 
                    to classify and understand plant species with local precision.
                </p>
            </div>
            <div class="col-md-6">
                <h4 class="text-warning"><i class="fas fa-rocket"></i> The Big Dream</h4>
                <p>
                    With real-time communication (RTC) and a constantly evolving AI model, we strive to push boundaries and create a smarter, more connected future.
                </p>
            </div>
        </div>

        <!-- Social Links -->
        <div class="mt-5">
            <h4>Follow Us</h4>
            <a href="https://instagram.com/parnabodh" target="_blank" class="btn btn-danger me-2">
                <i class="fab fa-instagram"></i> Instagram
            </a>
            <a href="https://x.com/ParnaBodh" target="_blank" class="btn btn-primary">
                <i class="fab fa-twitter"></i> Twitter
            </a>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

