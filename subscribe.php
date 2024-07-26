<?php
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['email'])) {
  echo json_encode(['success' => false, 'message' => 'Email is required']);
  exit;
}

$email = $data['email'];
$filename = 'subscribers.csv';

// Check if the file exists
$fileExists = file_exists($filename);

// Open the file in append mode
$file = fopen($filename, 'a');

if ($file) {
  // Write the header if the file is newly created
  if (!$fileExists) {
    fputcsv($file, ['ID', 'Email']);
  }

  // Get the next ID
  $id = 1;
  if ($fileExists) {
    $lines = file($filename, FILE_IGNORE_NEW_LINES);
    $lastLine = array_pop($lines);
    $lastData = str_getcsv($lastLine);
    $id = $lastData[0] + 1;
  }

  // Write the new data
  fputcsv($file, [$id, $email]);

  // Close the file
  fclose($file);

  echo json_encode(['success' => true]);
} else {
  echo json_encode(['success' => false, 'message' => 'Could not write to file']);
}
