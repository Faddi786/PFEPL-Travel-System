<?php
include ('includes/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photo'])) {
    $uploadDir = 'uploads/';
    $filename = uniqid('photo_') . '.jpg'; // Generate unique filename with .jpg extension
    // $filename = uniqid('photo_') ; // Generate unique filename with .jpg extension
    $uploadPath = $uploadDir . $filename;

    if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadPath)) {
        echo json_encode(['success' => true, 'imagePath' => $uploadPath]);
    } else {
        // Failed to move uploaded file
        echo json_encode(['success' => false, 'error' => 'Failed to move uploaded file']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method or missing file']);
}
?>