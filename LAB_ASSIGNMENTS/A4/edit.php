<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "student_db");
if ($conn->connect_error) {
    echo json_encode(["status"=>"error", "message"=>"DB connection failed"]);
    exit;
}

// FETCH for update form (GET)
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $result = $conn->query("SELECT * FROM students WHERE id=$id");
    if ($row = $result->fetch_assoc()) {
        echo json_encode(["status"=>"ok", "record"=>$row]);
    } else {
        echo json_encode(["status"=>"error", "message"=>"ID not found!"]);
    }
    $conn->close();
    exit;
}

// UPDATE student (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $age = isset($_POST['age']) ? intval($_POST['age']) : 0;

    if ($id > 0 && $name && $email && $age > 0) {
        $stmt = $conn->prepare("UPDATE students SET name=?, email=?, age=? WHERE id=?");
        $stmt->bind_param("ssii", $name, $email, $age, $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo json_encode(["status"=>"ok", "message"=>"Updated successfully!"]);
        } else {
            echo json_encode(["status"=>"ok", "message"=>"No changes made!"]);
        }
        $stmt->close();
    } else {
        echo json_encode(["status"=>"error", "message"=>"Invalid input!"]);
    }
    $conn->close();
    exit;
}
?>
