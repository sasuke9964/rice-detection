<?php
// Include the database connection and helper functions
require_once 'includes/config.php';
require_once 'includes/functions.php';

$analysis_complete = false;
$analysis_results = null;
$error_message = null;

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if file is uploaded
    if (isset($_FILES['rice_image']) && $_FILES['rice_image']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['rice_image'];
        
        // Validate file is an image
        $file_type = exif_imagetype($file['tmp_name']);
        if ($file_type !== false) {
            // Create unique filename
            $filename = uniqid('rice_') . '_' . date('Ymd') . image_file_extension($file_type);
            $upload_path = 'uploads/' . $filename;
            
            // Move uploaded file
            if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                // Process the image for analysis
                $results = analyze_rice_image($upload_path);
                
                if ($results) {
                    $analysis_complete = true;
                    $analysis_results = $results;
                    
                    // Save analysis results to database if user selected
                    if (isset($_POST['save_results']) && $_POST['save_results'] == 1) {
                        $sample_name = isset($_POST['sample_name']) ? $_POST['sample_name'] : 'Unnamed Sample';
                        $notes = isset($_POST['notes']) ? $_POST['notes'] : '';
                        
                        save_analysis_results($upload_path, $sample_name, $results, $notes);
                    }
                } else {
                    $error_message = "Error analyzing the image. Please try again.";
                }
            } else {
                $error_message = "Failed to upload image. Please try again.";
            }
        } else {
            $error_message = "Invalid file type. Please upload an image file.";
        }
    } else if (isset($_FILES['rice_image'])) {
        // Handle file upload errors
        $error_message = file_upload_error_message($_FILES['rice_image']['error']);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analyze Rice Sample - Rice Quality Analyzer</title>
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
                        <a class="nav-link active" href="analyze.php">Analyze</a>
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

    <!-- Main Content -->
    <div class="container py-5">
        <div id="alerts-container"></div>
        
        <?php if ($error_message): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $error_message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>
        
        <div class="row">
            <?php if (!$analysis_complete): ?>
            <!-- Analysis Form -->
            <div class="col-lg-8 mx-auto">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="fas fa-microscope me-2"></i>Rice Sample Analysis</h4>
                    </div>
                    <div class="card-body p-4">
                        <form id="analysis-form" action="analyze.php" method="post" enctype="multipart/form-data">
                            <div class="mb-4">
                                <label for="sample-name" class="form-label">Sample Name (Optional)</label>
                                <input type="text" class="form-control" id="sample-name" name="sample_name" placeholder="e.g., Basmati Rice Sample #1">
                            </div>
                            
                            <div class="mb-4">
                                <label for="rice-image" class="form-label">Upload Rice Image</label>
                                <div class="upload-area" onclick="document.getElementById('rice-image').click()">
                                    <div class="upload-icon">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                    </div>
                                    <p class="mb-0">Click or drag and drop your rice image here</p>
                                    <p class="text-muted small">Supports JPG, PNG, WEBP (Max: 5MB)</p>
                                    <input type="file" class="d-none" id="rice-image" name="rice_image" accept="image/*">
                                </div>
                                <div id="image-preview" class="mt-3 text-center"></div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="notes" class="form-label">Notes (Optional)</label>
                                <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Add any notes about this sample..."></textarea>
                            </div>
                            
                            <div class="mb-4 form-check">
                                <input type="checkbox" class="form-check-input" id="save-results" name="save_results" value="1" checked>
                                <label class="form-check-label" for="save-results">Save results to history</label>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" id="analyze-btn" class="btn btn-primary btn-lg" disabled>
                                    <i class="fas fa-microscope me-2"></i>Analyze Sample
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <!-- Analysis Results -->
            <div class="col-12">
                <div class="alert alert-success mb-4">
                    <i class="fas fa-check-circle me-2"></i>Analysis completed successfully!
                </div>
                
                <div class="row">
                    <div class="col-lg-4 mb-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Uploaded Sample</h5>
                            </div>
                            <div class="card-body text-center">
                                <img src="<?php echo $analysis_results['image_path']; ?>" alt="Rice Sample" class="img-fluid rounded mb-3" style="max-height: 300px;">
                                <h5><?php echo isset($_POST['sample_name']) && !empty($_POST['sample_name']) ? $_POST['sample_name'] : 'Unnamed Sample'; ?></h5>
                                <p class="text-muted small"><?php echo date('F j, Y, g:i a'); ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-8">
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
                                                        <td><?php echo $analysis_results['normal_count']; ?></td>
                                                        <td><?php echo $analysis_results['normal_percentage']; ?>%</td>
                                                    </tr>
                                                    <tr>
                                                        <td><span class="badge bg-warning">Broken Rice</span></td>
                                                        <td><?php echo $analysis_results['broken_count']; ?></td>
                                                        <td><?php echo $analysis_results['broken_percentage']; ?>%</td>
                                                    </tr>
                                                    <tr>
                                                        <td><span class="badge bg-danger">Black Spotted Rice</span></td>
                                                        <td><?php echo $analysis_results['black_spotted_count']; ?></td>
                                                        <td><?php echo $analysis_results['black_spotted_percentage']; ?>%</td>
                                                    </tr>
                                                    <tr class="table-light">
                                                        <td><strong>Total Grains</strong></td>
                                                        <td><?php echo $analysis_results['total_count']; ?></td>
                                                        <td>100%</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="chart-container" style="position: relative; height: 250px;">
                                            <canvas class="result-chart" 
                                                data-normal="<?php echo $analysis_results['normal_percentage']; ?>" 
                                                data-broken="<?php echo $analysis_results['broken_percentage']; ?>" 
                                                data-black-spotted="<?php echo $analysis_results['black_spotted_percentage']; ?>">
                                            </canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Quality Assessment</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-4">
                                    <h6>Overall Quality Score</h6>
                                    <div class="progress">
                                        <div class="progress-bar bg-<?php echo get_quality_color($analysis_results['quality_score']); ?>" 
                                            role="progressbar" 
                                            style="width: <?php echo $analysis_results['quality_score']; ?>%" 
                                            aria-valuenow="<?php echo $analysis_results['quality_score']; ?>" 
                                            aria-valuemin="0" 
                                            aria-valuemax="100">
                                            <?php echo $analysis_results['quality_score']; ?>%
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="card mb-0 bg-light">
                                    <div class="card-body">
                                        <h6>Assessment Summary</h6>
                                        <p><?php echo $analysis_results['assessment']; ?></p>
                                        
                                        <?php if (!empty($analysis_results['recommendations'])): ?>
                                        <h6>Recommendations</h6>
                                        <ul class="mb-0">
                                            <?php foreach ($analysis_results['recommendations'] as $recommendation): ?>
                                            <li><?php echo $recommendation; ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="text-center mt-4">
                    <a href="analyze.php" class="btn btn-primary me-2">
                        <i class="fas fa-redo me-2"></i>Analyze Another Sample
                    </a>
                    <a href="history.php" class="btn btn-outline-secondary">
                        <i class="fas fa-history me-2"></i>View Analysis History
                    </a>
                </div>
            </div>
            <?php endif; ?>
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