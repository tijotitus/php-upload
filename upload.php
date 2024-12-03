<?php
session_start();
require __DIR__ . DIRECTORY_SEPARATOR . 'src'.DIRECTORY_SEPARATOR.'UploadHandler.php';
require __DIR__ . DIRECTORY_SEPARATOR. 'generate_report.php';

use App\UploadHandler;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['images'])) {
    $uploadHandler = new UploadHandler(__DIR__ . DIRECTORY_SEPARATOR.'uploads');
    $results = [];
    $file_count = ['success_count' => 0, 'error_count' => 0];
    $files = $_FILES['images'];
    session_unset();
    foreach ($files['name'] as $key => $name) {
        $file = [
            'name' => $files['name'][$key],
            'type' => $files['type'][$key],
            'tmp_name' => $files['tmp_name'][$key],
            'error' => $files['error'][$key],
            'size' => $files['size'][$key],
        ];
        $result = $uploadHandler->upload($file);
        if(isset($result) && $result['success']) {
            $_SESSION['image_paths'][] = $result['filePath'];
            $file_count['success_count'] = $file_count['success_count'] + 1;
            $_SESSION['success_count'] =  $file_count['success_count'];
        } else {
            $file_count['error_count'] = $file_count['error_count'] + 1;
            $_SESSION['errors'][] = $result;
            $_SESSION['error_count'] =  $file_count['error_count'];
        }
        
    }

    // Generate report using an LLM
    //$report = generateReport($reportData);

    // Save report
    //file_put_contents(__DIR__ . DIRECTORY_SEPARATOR.'report.html', $report);

    // Redirect to index.php with messages
    header("Location: index.php?messages=");
    exit;
}
