<?php
// Include the necessary files
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
    <title>Print Report - <?php echo htmlspecialchars($result['sample_name']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            padding: 20px;
            color: #333;
        }
        .report-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #3498db;
        }
        .report-date {
            color: #777;
            font-size: 14px;
            margin-top: 5px;
        }
        .rice-image {
            max-height: 200px;
            display: block;
            margin: 0 auto 20px;
        }
        .quality-meter {
            height: 20px;
            background-color: #f0f0f0;
            border-radius: 10px;
            margin: 10px 0;
            overflow: hidden;
        }
        .quality-fill {
            height: 100%;
            border-radius: 10px;
        }
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #777;
            text-align: center;
        }
        @media print {
            .no-print {
                display: none;
            }
            a {
                text-decoration: none;
                color: #333;
            }
            .card {
                border: 1px solid #ddd !important;
            }
            .quality-fill {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Print Button (hidden when printing) -->
        <div class="text-end mb-4 no-print">
            <button onclick="window.print()" class="btn btn-primary">
                <i class="fas fa-print me-2"></i>Print Report
            </button>
            <a href="view_result.php?id=<?php echo $result['id']; ?>" class="btn btn-outline-secondary ms-2">
                <i class="fas fa-arrow-left me-2"></i>Back to Details
            </a>
        </div>
        
        <div class="report-header">
            <h1>Rice Quality Analysis Report</h1>
            <div class="report-date">Generated on <?php echo date('F j, Y, g:i a'); ?></div>
        </div>
        
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Sample Information</h5>
                    </div>
                    <div class="card-body">
                        <img src="<?php echo $result['image_path']; ?>" alt="Rice Sample" class="rice-image img-fluid rounded">
                        <div class="text-center mb-3">
                            <h4><?php echo htmlspecialchars($result['sample_name']); ?></h4>
                        </div>
                        
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Analysis ID:</strong>
                                <span>#<?php echo $result['id']; ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Analysis Date:</strong>
                                <span><?php echo date('F j, Y', strtotime($result['created_at'])); ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Analysis Time:</strong>
                                <span><?php echo date('g:i a', strtotime($result['created_at'])); ?></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Classification Results</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Category</th>
                                    <th>Count</th>
                                    <th>Percentage</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Normal Rice</td>
                                    <td><?php echo $result['normal_count']; ?></td>
                                    <td><?php echo $result['normal_percentage']; ?>%</td>
                                </tr>
                                <tr>
                                    <td>Broken Rice</td>
                                    <td><?php echo $result['broken_count']; ?></td>
                                    <td><?php echo $result['broken_percentage']; ?>%</td>
                                </tr>
                                <tr>
                                    <td>Black Spotted Rice</td>
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
                
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Quality Assessment</h5>
                    </div>
                    <div class="card-body">
                        <h6>Overall Quality Score: <?php echo $result['quality_score']; ?>%</h6>
                        <div class="quality-meter mb-4">
                            <div class="quality-fill bg-<?php echo get_quality_color($result['quality_score']); ?>" 
                                style="width: <?php echo $result['quality_score']; ?>%">
                            </div>
                        </div>
                        
                        <h6>Assessment Summary</h6>
                        <p><?php echo $result['assessment']; ?></p>
                        
                        <?php if (!empty($result['recommendations'])): ?>
                        <h6>Recommendations</h6>
                        <ul>
                            <?php foreach ($result['recommendations'] as $recommendation): ?>
                            <li><?php echo htmlspecialchars($recommendation); ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <?php endif; ?>
                        
                        <?php if (!empty($result['notes'])): ?>
                        <h6>Notes</h6>
                        <p><?php echo htmlspecialchars($result['notes']); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="footer">
            <p>This report was generated by Rice Quality Analyzer.</p>
            <p>&copy; <?php echo date('Y'); ?> Rice Quality Analyzer. All rights reserved.</p>
        </div>
    </div>
    
    <script>
        // Auto-print when the page loads
        window.onload = function() {
            // Uncomment the line below to automatically open the print dialog when the page loads
            // window.print();
        };
    </script>
</body>
</html> 