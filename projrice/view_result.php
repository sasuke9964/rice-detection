<?php
// Include the database connection and helper functions
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Check if ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    // Redirect to history page if no valid ID
    header('Location: history.php');
    exit;
}

$id = (int)$_GET['id'];
$result = get_analysis_by_id($id);

// If result not found, redirect to history
if (!$result) {
    header('Location: history.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Analysis Result - Rice Quality Analyzer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <!-- Chart.js for visualizations -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="analyze.php">Analyze</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="history.php">History</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php">About</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-file-alt me-2"></i>Analysis Result</h2>
            <div>
                <a href="history.php" class="btn btn-outline-secondary me-2">
                    <i class="fas fa-arrow-left me-2"></i>Back to History
                </a>
                <a href="analyze.php" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>New Analysis
                </a>
            </div>
        </div>
        
        <div class="row">
            <!-- Sample Information -->
            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Sample Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <img src="<?php echo $result['image_path']; ?>" alt="Rice Sample" class="img-fluid rounded mb-3" style="max-height: 250px;">
                            <h4><?php echo $result['sample_name']; ?></h4>
                        </div>
                        
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span>Analysis ID:</span>
                                <span class="badge bg-secondary rounded-pill">#<?php echo $result['id']; ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span>Date & Time:</span>
                                <span><?php echo date('F j, Y, g:i a', strtotime($result['created_at'])); ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span>Overall Quality:</span>
                                <span class="badge bg-<?php echo get_quality_color($result['quality_score']); ?>"><?php echo $result['quality_score']; ?>%</span>
                            </li>
                        </ul>
                        
                        <?php if (!empty($result['notes'])): ?>
                        <div class="mt-4">
                            <h6>Notes</h6>
                            <p class="mb-0 text-muted"><?php echo $result['notes']; ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Analysis Results -->
            <div class="col-lg-8">
                <!-- Classification Results -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Classification Results</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-7">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Category</th>
                                                <th>Count</th>
                                                <th>Percentage</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><span class="badge bg-success">Normal Rice</span></td>
                                                <td><?php echo $result['normal_count']; ?></td>
                                                <td><?php echo $result['normal_percentage']; ?>%</td>
                                            </tr>
                                            <tr>
                                                <td><span class="badge bg-warning">Broken Rice</span></td>
                                                <td><?php echo $result['broken_count']; ?></td>
                                                <td><?php echo $result['broken_percentage']; ?>%</td>
                                            </tr>
                                            <tr>
                                                <td><span class="badge bg-danger">Black Spotted Rice</span></td>
                                                <td><?php echo $result['black_spotted_count']; ?></td>
                                                <td><?php echo $result['black_spotted_percentage']; ?>%</td>
                                            </tr>
                                            <tr class="table-light">
                                                <td><strong>Total Grains</strong></td>
                                                <td><?php echo $result['total_count']; ?></td>
                                                <td>100%</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="chart-container" style="position: relative; height: 250px;">
                                    <canvas class="result-chart" 
                                        data-normal="<?php echo $result['normal_percentage']; ?>" 
                                        data-broken="<?php echo $result['broken_percentage']; ?>" 
                                        data-black-spotted="<?php echo $result['black_spotted_percentage']; ?>">
                                    </canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Detailed Assessment -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Quality Assessment</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <h6>Overall Quality Score</h6>
                            <div class="progress" style="height: 15px;">
                                <div class="progress-bar bg-<?php echo get_quality_color($result['quality_score']); ?>" 
                                    role="progressbar" 
                                    style="width: <?php echo $result['quality_score']; ?>%" 
                                    aria-valuenow="<?php echo $result['quality_score']; ?>" 
                                    aria-valuemin="0" 
                                    aria-valuemax="100">
                                    <?php echo $result['quality_score']; ?>%
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h6>Category-wise Quality Metrics</h6>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <div class="card bg-light">
                                        <div class="card-body p-3">
                                            <div class="d-flex justify-content-between mb-2">
                                                <h6 class="mb-0">Normal Rice</h6>
                                                <span class="badge bg-success"><?php echo $result['normal_percentage']; ?>%</span>
                                            </div>
                                            <div class="progress" style="height: 8px;">
                                                <div class="progress-bar bg-success" style="width: <?php echo $result['normal_percentage']; ?>%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="card bg-light">
                                        <div class="card-body p-3">
                                            <div class="d-flex justify-content-between mb-2">
                                                <h6 class="mb-0">Broken Rice</h6>
                                                <span class="badge bg-warning"><?php echo $result['broken_percentage']; ?>%</span>
                                            </div>
                                            <div class="progress" style="height: 8px;">
                                                <div class="progress-bar bg-warning" style="width: <?php echo $result['broken_percentage']; ?>%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="card bg-light">
                                        <div class="card-body p-3">
                                            <div class="d-flex justify-content-between mb-2">
                                                <h6 class="mb-0">Black Spotted</h6>
                                                <span class="badge bg-danger"><?php echo $result['black_spotted_percentage']; ?>%</span>
                                            </div>
                                            <div class="progress" style="height: 8px;">
                                                <div class="progress-bar bg-danger" style="width: <?php echo $result['black_spotted_percentage']; ?>%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card mb-0 bg-light">
                            <div class="card-body">
                                <h6>Assessment Summary</h6>
                                <p><?php echo $result['assessment']; ?></p>
                                
                                <?php if (!empty($result['recommendations'])): ?>
                                <h6>Recommendations</h6>
                                <ul class="mb-0">
                                    <?php foreach ($result['recommendations'] as $recommendation): ?>
                                    <li><?php echo $recommendation; ?></li>
                                    <?php endforeach; ?>
                                </ul>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Actions -->
                <div class="text-end">
                    <a href="download_report.php?id=<?php echo $result['id']; ?>" class="btn btn-outline-primary me-2">
                        <i class="fas fa-download me-2"></i>Download Report
                    </a>
                    <a href="print_report.php?id=<?php echo $result['id']; ?>" class="btn btn-outline-secondary me-2" target="_blank">
                        <i class="fas fa-print me-2"></i>Print
                    </a>
                    <a href="history.php?delete=<?php echo $result['id']; ?>" class="btn btn-outline-danger" 
                       onclick="return confirm('Are you sure you want to delete this analysis?');">
                        <i class="fas fa-trash-alt me-2"></i>Delete
                    </a>
                </div>
            </div>
        </div>
    </div>

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