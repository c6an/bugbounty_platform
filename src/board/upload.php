<?php

function exit_with_json_error($message, $error_code = 'N/A') {
    header('Content-Type: application/json');
    ob_clean();
    echo json_encode([
        'uploaded' => 0,
        'error' => ['message' => $message . ' Code: ' . $error_code]
    ]);
    exit();
}

function exit_with_callback_error($funcNum, $message) {
    echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '', 'Error: $message');</script>";
    exit();
}


if (isset($_FILES['upload']) && $_FILES['upload']['error'] == 0) {

    $upload_dir = '/var/www/html/uploads/';
    $base_url = '/uploads/';
    $funcNum = $_GET['CKEditorFuncNum'] ?? null;

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    if (is_writable($upload_dir)) {
        $safe_filename = preg_replace("/([^\w\s\d\-_~,;\[\]\(\).])|([\.]{2,})/", '', $_FILES['upload']['name']);
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        $file_extension = strtolower(pathinfo($safe_filename, PATHINFO_EXTENSION));

        if (!in_array($file_extension, $allowed_extensions)) {
            $error_message = 'Only image files (JPG, PNG, GIF) are allowed.';

            if ($funcNum !== null) {
                exit_with_callback_error($funcNum, $error_message);
            } else {
                exit_with_json_error($error_message, 'InvalidExtension');
            }
        }

        $destination = $upload_dir . $safe_filename;

        if (move_uploaded_file($_FILES['upload']['tmp_name'], $destination)) {
            $url = $base_url . $safe_filename;

            if ($funcNum !== null) {
                echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '$url', 'Image upload successful.');</script>";
            } else {
                exit_with_json_error('Paste upload is not fully configured in this example.', 'PasteUpload');
            }
        } else {
            if ($funcNum !== null) {
                exit_with_callback_error($funcNum, 'Could not move uploaded file.');
            } else {
                exit_with_json_error('Could not move uploaded file.');
            }
        }
    } else {
        if ($funcNum !== null) {
            exit_with_callback_error($funcNum, 'Upload directory is not writable.');
        } else {
            exit_with_json_error('Upload directory is not writable.');
        }
    }
} else {
    $error_code = $_FILES['upload']['error'] ?? 'N/A';
    $funcNum = $_GET['CKEditorFuncNum'] ?? null;
    if ($funcNum !== null) {
        exit_with_callback_error($funcNum, 'File upload failed. PHP error code: ' . $error_code);
    } else {
        exit_with_json_error('File upload failed. PHP error code: ', $error_code);
    }
}
?>