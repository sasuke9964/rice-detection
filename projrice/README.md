# Rice Quality Analyzer

An advanced web application for detecting and classifying rice quality issues, including broken grains and black spotted rice, using computer vision and image processing.

## Features

- Upload and analyze rice images
- Detect and classify normal rice, broken rice, and black spotted rice
- Generate detailed quality reports with metrics and recommendations
- View analysis history and detailed results
- Responsive design for desktop and mobile devices

## Technologies Used

- PHP for server-side processing
- MySQL for database storage
- JavaScript for client-side interactivity
- Bootstrap 5 for responsive design
- Chart.js for data visualization
- XAMPP for local development environment

## Installation and Setup

### Prerequisites

- XAMPP (or equivalent) with PHP 7.4+ and MySQL
- Web browser

### Setup Instructions

1. **Install XAMPP**
   - Download and install [XAMPP](https://www.apachefriends.org/index.html) based on your operating system.

2. **Clone/Download the Repository**
   - Clone this repository or download the files to your local machine.
   - Place all files in the `htdocs` directory of your XAMPP installation (e.g., `C:\xampp\htdocs\rice-analyzer\`).

3. **Start XAMPP Services**
   - Start the Apache and MySQL services from the XAMPP Control Panel.

4. **Access the Application**
   - Open your web browser and navigate to `http://localhost/rice-analyzer/` (or the appropriate path based on where you placed the files).
   - The application will create the necessary database and tables on first run.

5. **Create Uploads Directory**
   - The application will attempt to create an `uploads` directory automatically.
   - If there are permission issues, manually create an `uploads` directory in the project root and set appropriate write permissions.

## Usage

1. **Upload Rice Sample Images**
   - Go to the "Analyze" page and upload an image of rice grains.
   - Add an optional sample name and notes.

2. **View Analysis Results**
   - After uploading, the system will process the image and display classification results.
   - Results include counts and percentages of normal, broken, and black spotted rice.
   - A quality score and recommendations are provided.

3. **Browse Analysis History**
   - The "History" page shows all previously analyzed samples.
   - Search and filter by date or sample name.
   - View detailed results for any past analysis.

## Project Structure

- `index.php` - Main landing page
- `analyze.php` - Image upload and analysis page
- `history.php` - Analysis history page
- `view_result.php` - Detailed view of analysis results
- `about.php` - Information about the project and team
- `includes/` - Core PHP functions and configuration
  - `config.php` - Database configuration
  - `functions.php` - Helper functions
- `uploads/` - Uploaded rice images
- `css/` - Stylesheet files
- `js/` - JavaScript files
- `images/` - Static image assets

## Notes for Development

- The current version uses simulated classification for demonstration purposes.
- To implement actual rice grain detection and classification, modify the `analyze_rice_image()` function in `includes/functions.php` to integrate with computer vision libraries (e.g., OpenCV via PHP extensions or API calls to Python services).

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Acknowledgments

- Bootstrap for the responsive design framework
- Chart.js for data visualization
- Font Awesome for icons