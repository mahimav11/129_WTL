<?php
$conn = new mysqli("localhost", "root", "", "student_db"); 


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$name  = trim($_POST['name']);
$email = trim($_POST['email']);
$age   = intval($_POST['age']);

if ($name && $email && $age > 0) {
    $stmt = $conn->prepare("INSERT INTO students (name, email, age) VALUES (?, ?, ?)");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ssi", $name, $email, $age);
    $stmt->execute();

    echo ($stmt->affected_rows > 0) 
        ? "Student created successfully!" 
        : "Insert failed!";

    $stmt->close();
} else {
    echo "Invalid input!";
}

$conn->close();
?>
