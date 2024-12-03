<?php

namespace App;

class UploadHandler
{
    private string $uploadDir;
    private array $allowedFileTypes;
    private int $maxFileSize;

    private array $loadTimes = []; // To store load times for performance metrics

    public function __construct(string $uploadDir, array $allowedFileTypes = ['image/jpeg', 'image/png', 'image/gif'], int $maxFileSize = 5 * 1024 * 1024)
    {
        $this->uploadDir = rtrim($uploadDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $this->allowedFileTypes = $allowedFileTypes;
        $this->maxFileSize = $maxFileSize;

        // Ensure upload directory exists
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
    }

    public function upload(array $file): array
    {
        $startTime = microtime(true); // Start tracking time

        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'message' => $this->fileErrorMessage($file['error'])];
        }

        if (!$this->isValidFileType($file['type'])) {
            return ['success' => false, 'message' => 'Invalid file type. Only JPEG, PNG, and GIF are allowed.'];
        }

        if (!$this->isValidFileSize($file['size'])) {
            return ['success' => false, 'message' => 'File size exceeds the maximum limit of ' . ($this->maxFileSize / 1024 / 1024) . ' MB.'];
        }

        $uniqueFileName = $this->generateUniqueFileName($file['name']);
        $destination = 'uploads'. DIRECTORY_SEPARATOR . $uniqueFileName;

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            $endTime = microtime(true); // End tracking time
            $loadTime = $endTime - $startTime;
            $this->loadTimes[] = $loadTime; // Save load time

            return ['success' => true, 'message' => 'File uploaded successfully.', 'filePath' => $destination];
        } else {
            return ['success' => false, 'message' => 'Failed to move the uploaded file.'];
        }
    }

    public function getPerformanceMetrics(): array
    {
        $averageLoadTime = count($this->loadTimes) > 0 ? array_sum($this->loadTimes) / count($this->loadTimes) : 0;
        $peakLoad = !empty($this->loadTimes) ? max($this->loadTimes) : 0;

        return [
            'averageLoadTime' => round($averageLoadTime, 3), // Rounded to 3 decimal places
            'peakLoad' => round($peakLoad, 3),
        ];
    }

    private function isValidFileType(string $type): bool
    {
        return in_array($type, $this->allowedFileTypes, true);
    }

    private function isValidFileSize(int $size): bool
    {
        return $size <= $this->maxFileSize;
    }

    private function generateUniqueFileName(string $originalName): string
    {
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        return uniqid('img_', true) . '.' . $extension;
    }

    private function fileErrorMessage(int $errorCode): string
    {
        return match ($errorCode) {
            UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE => 'File size exceeds the allowed limit.',
            UPLOAD_ERR_PARTIAL => 'The file was only partially uploaded.',
            UPLOAD_ERR_NO_FILE => 'No file was uploaded.',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder.',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
            UPLOAD_ERR_EXTENSION => 'File upload stopped by a PHP extension.',
            default => 'Unknown error occurred.',
        };
    }
}
