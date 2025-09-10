<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "student_db");
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "DB connection failed"]);
    exit;
}
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

if ($id > 0) {
    $stmt = $conn->prepare("DELETE FROM students WHERE id=?");
    if ($stmt === false) {
        echo json_encode(["status" => "error", "message" => "DB statement error"]);
        $conn->close();
        exit;
    }
    $stmt->bind_param("i", $id);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        echo json_encode(["status" => "ok", "message" => "Deleted successfully!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Record not found!"]);
    }
    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid input!"]);
}
$conn->close();
exit;
?>
