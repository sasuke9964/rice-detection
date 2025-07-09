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

// In a real application, this would use a PDF library such as TCPDF or FPDF
// to generate a professionally formatted PDF report.
// For this demo, we'll create a simple HTML file for download that mimics a report.

// Generate HTML content for the report
$report_content = '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rice Analysis Report - ' . htmlspecialchars($result['sample_name']) . '</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
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
        h1, h2, h3 {
            color: #2c3e50;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            padding: 12px;
            text-align: left;
        }
        td {
            padding: 10px;
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
            background-color: #3498db;
            width: ' . $result['quality_score'] . '%;
        }
        .sample-image {
            max-width: 100%;
            height: auto;
            display: block;
            margin: 20px auto;
            border: 1px solid #ddd;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #777;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="report-header">
        <h1>Rice Quality Analysis Report</h1>
        <div class="report-date">Generated on ' . date('F j, Y, g:i a') . '</div>
    </div>
    
    <h2>Sample Information</h2>
    <table>
        <tr>
            <th>Sample Name</th>
            <td>' . htmlspecialchars($result['sample_name']) . '</td>
        </tr>
        <tr>
            <th>Analysis Date</th>
            <td>' . date('F j, Y, g:i a', strtotime($result['created_at'])) . '</td>
        </tr>
        <tr>
            <th>Analysis ID</th>
            <td>#' . $result['id'] . '</td>
        </tr>
    </table>
    
    <h2>Classification Results</h2>
    <table>
        <tr>
            <th>Category</th>
            <th>Count</th>
            <th>Percentage</th>
        </tr>
        <tr>
            <td>Normal Rice</td>
            <td>' . $result['normal_count'] . '</td>
            <td>' . $result['normal_percentage'] . '%</td>
        </tr>
        <tr>
            <td>Broken Rice</td>
            <td>' . $result['broken_count'] . '</td>
            <td>' . $result['broken_percentage'] . '%</td>
        </tr>
        <tr>
            <td>Black Spotted Rice</td>
            <td>' . $result['black_spotted_count'] . '</td>
            <td>' . $result['black_spotted_percentage'] . '%</td>
        </tr>
        <tr>
            <td><strong>Total Grains</strong></td>
            <td>' . $result['total_count'] . '</td>
            <td>100%</td>
        </tr>
    </table>
    
    <h2>Quality Assessment</h2>
    <h3>Overall Quality Score: ' . $result['quality_score'] . '%</h3>
    <div class="quality-meter">
        <div class="quality-fill"></div>
    </div>
    
    <h3>Assessment Summary</h3>
    <p>' . $result['assessment'] . '</p>
    ';
    
    // Add recommendations if available
    if (!empty($result['recommendations'])) {
        $report_content .= '<h3>Recommendations</h3><ul>';
        foreach ($result['recommendations'] as $recommendation) {
            $report_content .= '<li>' . htmlspecialchars($recommendation) . '</li>';
        }
        $report_content .= '</ul>';
    }
    
    // Add notes if available
    if (!empty($result['notes'])) {
        $report_content .= '<h3>Notes</h3><p>' . htmlspecialchars($result['notes']) . '</p>';
    }
    
    // Add footer
    $report_content .= '
    <div class="footer">
        <p>This report was generated by Rice Quality Analyzer.</p>
        <p>&copy; ' . date('Y') . ' Rice Quality Analyzer. All rights reserved.</p>
    </div>
</body>
</html>';

// Set headers for download
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="Rice_Analysis_Report_' . $result['id'] . '.html"');
header('Content-Length: ' . strlen($report_content));

// Output the report content
echo $report_content;
exit;
?> 