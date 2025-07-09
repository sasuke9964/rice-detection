<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rice Quality Analyzer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-seedling me-2"></i>
                Rice Quality Analyzer
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="analyze.php">Analyze</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="history.php">History</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php">About</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="hero-section py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">Advanced Rice Quality Analysis</h1>
                    <p class="lead mb-4">Detect and classify rice quality issues including broken grains and black spotted rice with our AI-powered analysis system.</p>
                    <div class="d-flex">
                        <a href="analyze.php" class="btn btn-primary btn-lg me-3">
                            <i class="fas fa-microscope me-2"></i>Analyze Rice Sample
                        </a>
                        <a href="about.php" class="btn btn-outline-secondary btn-lg">
                            <i class="fas fa-info-circle me-2"></i>Learn More
                        </a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <img src="images/rice-hero.jpg" alt="Rice Analysis" class="img-fluid rounded shadow-lg">
                </div>
            </div>
        </div>
    </header>

    <!-- Features Section -->
    <section class="features-section py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5">Key Features</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon bg-primary text-white rounded-circle mb-3">
                                <i class="fas fa-camera"></i>
                            </div>
                            <h4>Image Analysis</h4>
                            <p>Upload rice images for instant quality assessment with our advanced image processing algorithms.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon bg-primary text-white rounded-circle mb-3">
                                <i class="fas fa-chart-pie"></i>
                            </div>
                            <h4>Classification Report</h4>
                            <p>Receive detailed reports showing percentages of broken grains, black spots, and other quality metrics.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center p-4">
                            <div class="feature-icon bg-primary text-white rounded-circle mb-3">
                                <i class="fas fa-history"></i>
                            </div>
                            <h4>Analysis History</h4>
                            <p>Track all your previous analysis results to monitor quality trends over time.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="process-section py-5">
        <div class="container">
            <h2 class="text-center mb-5">How It Works</h2>
            <div class="row">
                <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                    <div class="process-step text-center">
                        <div class="process-icon bg-primary text-white rounded-circle mb-3">1</div>
                        <h4>Upload</h4>
                        <p>Upload a high-quality image of your rice sample</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                    <div class="process-step text-center">
                        <div class="process-icon bg-primary text-white rounded-circle mb-3">2</div>
                        <h4>Process</h4>
                        <p>Our system processes the image using advanced algorithms</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <div class="process-step text-center">
                        <div class="process-icon bg-primary text-white rounded-circle mb-3">3</div>
                        <h4>Analyze</h4>
                        <p>AI classifies rice grains and identifies quality issues</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="process-step text-center">
                        <div class="process-icon bg-primary text-white rounded-circle mb-3">4</div>
                        <h4>Results</h4>
                        <p>Receive a detailed report with quality metrics</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="fas fa-seedling me-2"></i>Rice Quality Analyzer</h5>
                    <p>Advanced rice adulteration and classification system for quality control and assessment.</p>
                </div>
                <div class="col-md-3">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="index.php" class="text-white">Home</a></li>
                        <li><a href="analyze.php" class="text-white">Analyze</a></li>
                        <li><a href="history.php" class="text-white">History</a></li>
                        <li><a href="about.php" class="text-white">About</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>Contact</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-envelope me-2"></i>info@riceanalyzer.com</li>
                        <li><i class="fas fa-phone me-2"></i>(123) 456-7890</li>
                    </ul>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <p class="mb-0">&copy; 2024 Rice Quality Analyzer. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html> 