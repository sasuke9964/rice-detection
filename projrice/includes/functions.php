<?php
/**
 * Rice Quality Analyzer - Helper Functions
 */

/**
 * Get file extension based on image type
 *
 * @param int $image_type IMAGETYPE constant
 * @return string File extension with dot
 */
function image_file_extension($image_type) {
    switch ($image_type) {
        case IMAGETYPE_JPEG:
            return '.jpg';
        case IMAGETYPE_PNG:
            return '.png';
        case IMAGETYPE_GIF:
            return '.gif';
        case IMAGETYPE_WEBP:
            return '.webp';
        default:
            return '.jpg';
    }
}

/**
 * Get error message for file upload errors
 *
 * @param int $error_code PHP file upload error code
 * @return string Error message
 */
function file_upload_error_message($error_code) {
    switch ($error_code) {
        case UPLOAD_ERR_INI_SIZE:
            return "The uploaded file exceeds the upload_max_filesize directive in php.ini.";
        case UPLOAD_ERR_FORM_SIZE:
            return "The uploaded file exceeds the MAX_FILE_SIZE directive in the HTML form.";
        case UPLOAD_ERR_PARTIAL:
            return "The uploaded file was only partially uploaded.";
        case UPLOAD_ERR_NO_FILE:
            return "No file was uploaded.";
        case UPLOAD_ERR_NO_TMP_DIR:
            return "Missing a temporary folder.";
        case UPLOAD_ERR_CANT_WRITE:
            return "Failed to write file to disk.";
        case UPLOAD_ERR_EXTENSION:
            return "A PHP extension stopped the file upload.";
        default:
            return "Unknown upload error.";
    }
}

/**
 * Analyze rice image and return classification results
 * 
 * Note: This is a simplified demo function. In a real-world application,
 * this function would integrate with image processing libraries and ML models
 * to analyze and classify rice grains.
 *
 * @param string $image_path Path to the uploaded image
 * @return array|false Classification results or false on error
 */
function analyze_rice_image($image_path) {
    // In a real application, you would:
    // 1. Use image processing libraries (e.g., OpenCV) to process the image
    // 2. Identify individual rice grains
    // 3. Extract features from each grain
    // 4. Classify grains as normal, broken, or black spotted
    // 5. Calculate statistics
    
    // For this demo, we'll simulate analysis with random values
    
    // Total number of rice grains (random for demo)
    $total_count = rand(50, 150);
    
    // Calculate counts for each category
    $normal_percentage = rand(50, 95);
    $broken_percentage = rand(5, 30);
    $black_spotted_percentage = 100 - $normal_percentage - $broken_percentage;
    
    // Ensure black spotted percentage is not negative
    if ($black_spotted_percentage < 0) {
        $black_spotted_percentage = 0;
        $normal_percentage = 100 - $broken_percentage;
    }
    
    $normal_count = round($total_count * ($normal_percentage / 100));
    $broken_count = round($total_count * ($broken_percentage / 100));
    $black_spotted_count = $total_count - $normal_count - $broken_count;
    
    // Calculate quality score (weighted score based on percentages)
    $quality_score = $normal_percentage - ($broken_percentage * 0.5) - ($black_spotted_percentage * 2);
    if ($quality_score < 0) $quality_score = 0;
    if ($quality_score > 100) $quality_score = 100;
    
    // Generate assessment text
    $assessment = generate_assessment($normal_percentage, $broken_percentage, $black_spotted_percentage, $quality_score);
    
    // Generate recommendations
    $recommendations = generate_recommendations($normal_percentage, $broken_percentage, $black_spotted_percentage, $quality_score);
    
    // Return analysis results
    return [
        'image_path' => $image_path,
        'total_count' => $total_count,
        'normal_count' => $normal_count,
        'normal_percentage' => $normal_percentage,
        'broken_count' => $broken_count,
        'broken_percentage' => $broken_percentage,
        'black_spotted_count' => $black_spotted_count,
        'black_spotted_percentage' => $black_spotted_percentage,
        'quality_score' => $quality_score,
        'assessment' => $assessment,
        'recommendations' => $recommendations
    ];
}

/**
 * Generate assessment text based on analysis results
 *
 * @param float $normal_percentage Percentage of normal rice
 * @param float $broken_percentage Percentage of broken rice
 * @param float $black_spotted_percentage Percentage of black spotted rice
 * @param float $quality_score Overall quality score
 * @return string Assessment text
 */
function generate_assessment($normal_percentage, $broken_percentage, $black_spotted_percentage, $quality_score) {
    if ($quality_score >= 90) {
        return "This is an excellent quality rice sample with a high percentage of normal grains. The presence of broken grains and black spotted rice is minimal, making this a premium grade sample suitable for high-end markets.";
    } else if ($quality_score >= 75) {
        return "This is a good quality rice sample with a significant majority of normal grains. There is a small presence of broken grains and minimal black spots, making this appropriate for standard consumer markets.";
    } else if ($quality_score >= 60) {
        return "This is an average quality rice sample. While there is still a majority of normal grains, the presence of broken grains and some black spotted rice reduces the overall quality. This would be suitable for general consumption but may not meet premium standards.";
    } else if ($quality_score >= 40) {
        return "This is a below-average quality rice sample with significant quality issues. The higher percentage of broken grains and black spotted rice indicates problems in processing or storage. This may be acceptable for certain commercial applications but not for direct consumer sales.";
    } else {
        return "This is a poor quality rice sample with major quality concerns. The high percentage of broken grains and black spotted rice indicates serious issues in cultivation, processing, or storage. This would not meet standard market requirements and requires significant improvement.";
    }
}

/**
 * Generate recommendations based on analysis results
 *
 * @param float $normal_percentage Percentage of normal rice
 * @param float $broken_percentage Percentage of broken rice
 * @param float $black_spotted_percentage Percentage of black spotted rice
 * @param float $quality_score Overall quality score
 * @return array List of recommendations
 */
function generate_recommendations($normal_percentage, $broken_percentage, $black_spotted_percentage, $quality_score) {
    $recommendations = [];
    
    // Recommendations for broken rice
    if ($broken_percentage > 20) {
        $recommendations[] = "High percentage of broken rice detected. Consider adjusting milling parameters or upgrading equipment to reduce breakage.";
    } else if ($broken_percentage > 10) {
        $recommendations[] = "Moderate percentage of broken rice detected. Review rice handling procedures during harvesting and post-processing.";
    }
    
    // Recommendations for black spotted rice
    if ($black_spotted_percentage > 15) {
        $recommendations[] = "Significant black spotted rice detected. Investigate potential fungal contamination or inadequate drying procedures.";
    } else if ($black_spotted_percentage > 5) {
        $recommendations[] = "Some black spotted rice detected. Review storage conditions and ensure proper moisture control.";
    }
    
    // General recommendations based on quality score
    if ($quality_score < 50) {
        $recommendations[] = "Overall quality is low. Consider thorough review of entire rice processing chain from cultivation to storage.";
    }
    
    // If quality is good, add a positive recommendation
    if ($quality_score >= 80) {
        $recommendations[] = "This is high-quality rice. Maintain current practices and consider marketing as premium grade.";
    }
    
    return $recommendations;
}

/**
 * Save analysis results to the database
 *
 * @param string $image_path Path to the analyzed image
 * @param string $sample_name Name of the sample
 * @param array $results Analysis results
 * @param string $notes Optional notes
 * @return int|bool The ID of the inserted record or false on failure
 */
function save_analysis_results($image_path, $sample_name, $results, $notes = '') {
    global $conn;
    
    $recommendations_json = json_encode(isset($results['recommendations']) ? $results['recommendations'] : []);
    
    $sql = "INSERT INTO analysis_results (
                sample_name, 
                image_path, 
                normal_count, 
                normal_percentage, 
                broken_count, 
                broken_percentage, 
                black_spotted_count, 
                black_spotted_percentage, 
                total_count, 
                quality_score, 
                assessment, 
                recommendations, 
                notes
            ) VALUES (
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
            )";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        'ssididididsss',
        $sample_name,
        $image_path,
        $results['normal_count'],
        $results['normal_percentage'],
        $results['broken_count'],
        $results['broken_percentage'],
        $results['black_spotted_count'],
        $results['black_spotted_percentage'],
        $results['total_count'],
        $results['quality_score'],
        $results['assessment'],
        $recommendations_json,
        $notes
    );
    
    if ($stmt->execute()) {
        return $conn->insert_id;
    }
    
    return false;
}

/**
 * Get analysis history with pagination
 *
 * @param int $limit Number of records per page
 * @param int $offset Offset for pagination
 * @param string $search Optional search term
 * @param string $date_from Optional start date
 * @param string $date_to Optional end date
 * @return array Analysis records
 */
function get_analysis_history($limit = 10, $offset = 0, $search = '', $date_from = '', $date_to = '') {
    global $conn;
    
    $where_clauses = [];
    $params = [];
    $types = '';
    
    // Add search condition if provided
    if (!empty($search)) {
        $where_clauses[] = "sample_name LIKE ?";
        $params[] = "%$search%";
        $types .= 's';
    }
    
    // Add date range conditions if provided
    if (!empty($date_from)) {
        $where_clauses[] = "DATE(created_at) >= ?";
        $params[] = $date_from;
        $types .= 's';
    }
    
    if (!empty($date_to)) {
        $where_clauses[] = "DATE(created_at) <= ?";
        $params[] = $date_to;
        $types .= 's';
    }
    
    $where_sql = !empty($where_clauses) ? "WHERE " . implode(" AND ", $where_clauses) : "";
    
    $sql = "SELECT * FROM analysis_results $where_sql ORDER BY created_at DESC LIMIT ?, ?";
    $params[] = $offset;
    $params[] = $limit;
    $types .= 'ii';
    
    $stmt = $conn->prepare($sql);
    
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    $records = [];
    
    while ($row = $result->fetch_assoc()) {
        // Convert JSON recommendations back to array
        if (isset($row['recommendations'])) {
            $row['recommendations'] = json_decode($row['recommendations'], true);
        }
        $records[] = $row;
    }
    
    return $records;
}

/**
 * Get total count of analysis records
 *
 * @param string $search Optional search term
 * @param string $date_from Optional start date
 * @param string $date_to Optional end date
 * @return int Total number of records
 */
function get_total_analysis_count($search = '', $date_from = '', $date_to = '') {
    global $conn;
    
    $where_clauses = [];
    $params = [];
    $types = '';
    
    // Add search condition if provided
    if (!empty($search)) {
        $where_clauses[] = "sample_name LIKE ?";
        $params[] = "%$search%";
        $types .= 's';
    }
    
    // Add date range conditions if provided
    if (!empty($date_from)) {
        $where_clauses[] = "DATE(created_at) >= ?";
        $params[] = $date_from;
        $types .= 's';
    }
    
    if (!empty($date_to)) {
        $where_clauses[] = "DATE(created_at) <= ?";
        $params[] = $date_to;
        $types .= 's';
    }
    
    $where_sql = !empty($where_clauses) ? "WHERE " . implode(" AND ", $where_clauses) : "";
    
    $sql = "SELECT COUNT(*) AS total FROM analysis_results $where_sql";
    
    $stmt = $conn->prepare($sql);
    
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    return $row['total'];
}

/**
 * Get a specific analysis record by ID
 *
 * @param int $id Record ID
 * @return array|false Analysis record or false if not found
 */
function get_analysis_by_id($id) {
    global $conn;
    
    $sql = "SELECT * FROM analysis_results WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        return false;
    }
    
    $record = $result->fetch_assoc();
    
    // Convert JSON recommendations back to array
    if (isset($record['recommendations'])) {
        $record['recommendations'] = json_decode($record['recommendations'], true);
    }
    
    return $record;
}

/**
 * Delete an analysis record
 *
 * @param int $id Record ID
 * @return bool True on success, false on failure
 */
function delete_analysis_record($id) {
    global $conn;
    
    // First get the record to delete the image file
    $record = get_analysis_by_id($id);
    
    if ($record) {
        // Delete the image file if it exists
        if (file_exists($record['image_path'])) {
            unlink($record['image_path']);
        }
        
        // Delete the record from the database
        $sql = "DELETE FROM analysis_results WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);
        
        return $stmt->execute();
    }
    
    return false;
}

/**
 * Get appropriate color class based on quality score
 *
 * @param float $score Quality score
 * @return string Bootstrap color class
 */
function get_quality_color($score) {
    if ($score >= 90) {
        return 'success';
    } else if ($score >= 70) {
        return 'info';
    } else if ($score >= 50) {
        return 'primary';
    } else if ($score >= 30) {
        return 'warning';
    } else {
        return 'danger';
    }
}
?> 