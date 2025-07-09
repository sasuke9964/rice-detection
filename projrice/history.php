<?php
// Include the database connection and helper functions
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Get page number for pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 10;
$offset = ($page - 1) * $per_page;

// Get analysis history
$analysis_history = get_analysis_history($per_page, $offset);
$total_records = get_total_analysis_count();
$total_pages = ceil($total_records / $per_page);

// Handle deletion if requested
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    delete_analysis_record($id);
    
    // Redirect to remove the delete parameter
    header('Location: history.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analysis History - Rice Quality Analyzer</title>
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
        <div class="row mb-4">
            <div class="col-md-8">
                <h2><i class="fas fa-history me-2"></i>Rice Analysis History</h2>
                <p class="text-muted">View and manage your previous rice quality analysis results.</p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="analyze.php" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>New Analysis
                </a>
            </div>
        </div>

        <?php if (empty($analysis_history)): ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>You haven't performed any rice analyses yet. 
            <a href="analyze.php" class="alert-link">Start your first analysis now</a>.
        </div>
        <?php else: ?>
        
        <!-- Search & Filter Box -->
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-body">
                <form action="history.php" method="get" class="row g-3">
                    <div class="col-md-6">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" class="form-control" id="search" name="search" placeholder="Search by sample name...">
                    </div>
                    <div class="col-md-3">
                        <label for="date-from" class="form-label">Date From</label>
                        <input type="date" class="form-control" id="date-from" name="date_from">
                    </div>
                    <div class="col-md-3">
                        <label for="date-to" class="form-label">Date To</label>
                        <input type="date" class="form-control" id="date-to" name="date_to">
                    </div>
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-2"></i>Search
                        </button>
                        <a href="history.php" class="btn btn-outline-secondary">
                            <i class="fas fa-redo me-2"></i>Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>
            
        <!-- Analysis History Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 history-table">
                        <thead>
                            <tr>
                                <th style="width: 80px;">ID</th>
                                <th style="width: 120px;">Image</th>
                                <th>Sample Name</th>
                                <th>Quality Score</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($analysis_history as $record): ?>
                            <tr>
                                <td>#<?php echo $record['id']; ?></td>
                                <td>
                                    <img src="<?php echo $record['image_path']; ?>" alt="Rice Sample" class="img-thumbnail" style="width: 80px; height: 60px; object-fit: cover;">
                                </td>
                                <td><?php echo $record['sample_name']; ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                            <div class="progress-bar bg-<?php echo get_quality_color($record['quality_score']); ?>" 
                                                role="progressbar" 
                                                style="width: <?php echo $record['quality_score']; ?>%" 
                                                aria-valuenow="<?php echo $record['quality_score']; ?>" 
                                                aria-valuemin="0" 
                                                aria-valuemax="100">
                                            </div>
                                        </div>
                                        <span><?php echo $record['quality_score']; ?>%</span>
                                    </div>
                                </td>
                                <td><?php echo date('M j, Y g:i A', strtotime($record['created_at'])); ?></td>
                                <td>
                                    <a href="view_result.php?id=<?php echo $record['id']; ?>" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="history.php?delete=<?php echo $record['id']; ?>" class="btn btn-sm btn-danger" 
                                       onclick="return confirm('Are you sure you want to delete this record?');" 
                                       data-bs-toggle="tooltip" title="Delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
        <nav aria-label="Analysis history pagination" class="mt-4">
            <ul class="pagination justify-content-center">
                <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="<?php echo ($page <= 1) ? '#' : 'history.php?page=' . ($page - 1); ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                
                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                    <a class="page-link" href="history.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
                <?php endfor; ?>
                
                <li class="page-item <?php echo ($page >= $total_pages) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="<?php echo ($page >= $total_pages) ? '#' : 'history.php?page=' . ($page + 1); ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
        <?php endif; ?>
        
        <?php endif; ?>
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