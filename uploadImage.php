<?php
// Check if image data and type are set in the POST request
if (isset($_POST['image']) && isset($_POST['type'])) {
    $imageData = $_POST['image']; // Image data in Blob format
    $type = $_POST['type']; // Type of image ('start_photo' or 'end_photo')
    
    // Define the folder where images will be saved
    $folder = './static/images/';

    // Determine the folder based on the type of image
    if ($type === 'start_photo') {
        $folder .= 'id_start/';
    } else if ($type === 'end_photo') {
        $folder .= 'id_end/';
    }

    // Ensure the folder exists; create it if it doesn't
    if (!file_exists($folder)) {
        mkdir($folder, 0777, true); // Create folder recursively with full permissions
    }

    // Generate a unique ID for the image file
    $id = uniqid();

    // Define the file path where the image will be saved
    $filePath = $folder . $id . '.png';

    // Decode the image data from Blob format
    $data = base64_decode($imageData);

    // Save the image data to the specified file path
    if (file_put_contents($filePath, $data)) {
        // If image is successfully saved, echo the file path
        echo $filePath;
    } else {
        // If there's an error saving the image, echo an error message
        echo "Error: Failed to save the image.";
    }
} else {
    // If image data or type is not set in the POST request, echo an error message
    echo "Error: Image data or type not received.";
}
?>
