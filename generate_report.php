<?php

function generateReport(array $metrics): string
{
    $apiKey = 'apikey'; // Load API key from .env or environment variable
    if (!$apiKey) {
        return "Error: API key not configured.";
    }

    $apiUrl = "https://api.openai.com/v1/chat/completions";
    $data = [
        'model' => 'gpt-3.5-turbo-instruct', // Specify the model you want to use
        'messages' => [
            [
                'role' => 'system',
                'content' => 'You are an expert analyst generating reports from performance metrics.',
            ],
            [
                'role' => 'user',
                'content' => "Analyze the following performance metrics and generate a detailed report:\n\n" . json_encode($metrics, JSON_PRETTY_PRINT),
            ],
        ],
        'max_tokens' => 500, // Adjust as needed
        'temperature' => 0, // Adjust for creativity
    ];

    // Make HTTP request to OpenAI API
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Bearer $apiKey",
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    var_dump($httpCode);exit;
    if ($httpCode !== 200) {
        return "Error: Unable to fetch report. HTTP code $httpCode.";
    }

    $responseData = json_decode($response, true);
    return $responseData['choices'][0]['message']['content'] ?? 'Report generation failed.';
}

