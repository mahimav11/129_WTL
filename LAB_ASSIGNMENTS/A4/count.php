<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "student_db");

if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "DB connection failed"]);
    exit;
}

$sql = "SELECT COUNT(*) AS total FROM students";
$result = $conn->query($sql);

if ($result) {
    $row = $result->fetch_assoc();
    echo json_encode(["status" => "ok", "count" => intval($row["total"])]);
} else {
    echo json_encode(["status" => "error", "message" => "Query failed"]);
}

$conn->close();
exit;
?>
