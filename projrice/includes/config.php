<?php
/**
 * Rice Quality Analyzer - Configuration
 */

// Error reporting (disable in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');         // Default XAMPP MySQL username
define('DB_PASS', '');             // Default XAMPP MySQL password is empty
define('DB_NAME', 'rice_analyzer');

// Create database connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    // If database doesn't exist, create it
    if ($conn->connect_errno == 1049) {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS);
        
        // Create database
        $sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME;
        if ($conn->query($sql) === TRUE) {
            // Connect to the new database
            $conn->select_db(DB_NAME);
            
            // Create tables
            create_database_tables($conn);
        } else {
            die("Error creating database: " . $conn->error);
        }
    } else {
        die("Connection failed: " . $conn->connect_error);
    }
}

// Set charset
$conn->set_charset("utf8");

/**
 * Create necessary database tables
 */
function create_database_tables($conn) {
    // Create analysis_results table
    $sql = "CREATE TABLE IF NOT EXISTS analysis_results (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        sample_name VARCHAR(255) NOT NULL,
        image_path VARCHAR(255) NOT NULL,
        normal_count INT(11) NOT NULL DEFAULT 0,
        normal_percentage DECIMAL(5,2) NOT NULL DEFAULT 0.00,
        broken_count INT(11) NOT NULL DEFAULT 0,
        broken_percentage DECIMAL(5,2) NOT NULL DEFAULT 0.00,
        black_spotted_count INT(11) NOT NULL DEFAULT 0,
        black_spotted_percentage DECIMAL(5,2) NOT NULL DEFAULT 0.00,
        total_count INT(11) NOT NULL DEFAULT 0,
        quality_score DECIMAL(5,2) NOT NULL DEFAULT 0.00,
        assessment TEXT,
        recommendations TEXT,
        notes TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
    
    if (!$conn->query($sql)) {
        die("Error creating table: " . $conn->error);
    }
    
    // Create other tables as needed
}

// Application settings
define('SITE_NAME', 'Rice Quality Analyzer');
define('UPLOAD_DIR', 'uploads/');

// Create uploads directory if it doesn't exist
if (!file_exists(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0777, true);
}
?> 