<?php
include "../db.php";

$userid = $_POST['user_id'];
$response = array('exists' => false);

if (!empty(trim($userid))) {

    $sql = "SELECT COUNT(*) as count FROM user WHERE user_id = ?";
    $stmt = $db_conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("s", $userid);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if ($row['count'] > 0) {
            $response['exists'] = true;
        }
        $stmt->close();
    }
}

$db_conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>