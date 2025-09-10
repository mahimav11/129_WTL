<?php
$conn = new mysqli("localhost", "root", "", "student_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Case 1: If ID is sent → return one student
if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $stmt = $conn->prepare("SELECT id, name, email, age FROM students WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $row['exists'] = true;  // 🔹 Mark as exists
        echo json_encode($row);
    } else {
        echo json_encode(["exists" => false]);
    }

    $stmt->close();
} 
// Case 2: No ID → return all students
else {
    $stmt = $conn->prepare("SELECT id, name, email, age FROM students");
    $stmt->execute();
    $result = $stmt->get_result();
    $arr = [];

    while ($row = $result->fetch_assoc()) {
        $arr[] = $row;
    }

    echo json_encode($arr);
    $stmt->close();
}

$conn->close();
?>
