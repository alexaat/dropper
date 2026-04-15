<?php

// if ($_SERVER['REQUEST_METHOD'] === 'GET') {
//     echo 'ping';
//     return;
// }



/*
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['file'])) {
        $file = $_FILES['file'];

        $uploadDir = 'uploads/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = basename($file['name']);
        $targetFile = $uploadDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            echo json_encode([
                "success" => true,
                "message" => "File uploaded successfully",
                "file" => $fileName
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Failed to move uploaded file"
            ]);
        }

    } else {
        echo json_encode([
            "success" => false,
            "message" => "No file received"
        ]);
    }

} else {
    echo json_encode([
        "success" => false,
        "message" => "Invalid request method"
    ]);
}
    */

header("Access-Control-Allow-Origin: http://localhost:8000");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['file'])) {
        $file = $_FILES['file'];

        $uploadDir = 'uploads/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = basename($file['name']);
        $urlPath = floor(microtime(true) * 1000)."-".uniqid();
        $targetFile = $uploadDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetFile)) {           
            echo json_encode([
                "success" => true,
                "message" => "File uploaded successfully",
                "file" => $fileName,
                "url" => $urlPath
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Failed to move uploaded file"
            ]);
        }

    } else {
        echo json_encode([
            "success" => false,
            "message" => "No file received"
        ]);
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'GET'){
    $uri = $_SERVER['REQUEST_URI'];
    $prefix = '/serve.php?uid=';

    if (substr($uri, 0, strlen($prefix)) == $prefix) {
        $uid = substr($uri, strlen($prefix));      

        serve_file();

    }  

} else {
    echo json_encode([
        "success" => false,
        "message" => "Invalid request method"
    ]);
}


function serve_file(){
    $file = 'uploads/result-image.png';
    if (!file_exists($file)) {
        http_response_code(404);
        echo "File not found.";
        exit;
    }
    // Get file info
    $filename = basename($file);
    $filesize = filesize($file);

    // Set headers to force download
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Content-Length: ' . $filesize);
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Expires: 0');

    // Clear output buffer
    ob_clean();
    flush();

    // Read the file and output it
    readfile($file);
    exit;

}

?>