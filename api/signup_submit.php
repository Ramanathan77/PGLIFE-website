<?php
require("../includes/database_connect.php");

$full_name = $_POST['full_name'];
$phone = $_POST['phone'];
$email = $_POST['email'];
$password = $_POST['password'];
$password_hashed = password_hash($password, PASSWORD_DEFAULT);
$college_name = $_POST['college_name'];
$gender = $_POST['gender'];

$stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email=?");
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result) {
    $response = array("success" => false, "message" => "Something went wrong!");
    echo json_encode($response);
    return;
}

$row_count = mysqli_num_rows($result);
if ($row_count != 0) {
    $response = array("success" => false, "message" => "This email id is already registered with us!");
    echo json_encode($response);
    return;
}

$stmt2 = mysqli_prepare($conn, "INSERT INTO users (email, password, full_name, phone, gender, college_name) VALUES (?, ?, ?, ?, ?, ?)");
mysqli_stmt_bind_param($stmt2, "ssssss", $email, $password_hashed, $full_name, $phone, $gender, $college_name);
$result2 = mysqli_stmt_execute($stmt2);

if (!$result2) {
    $response = array("success" => false, "message" => "Something went wrong!");
    echo json_encode($response);
    return;
}

$response = array("success" => true, "message" => "Your account has been created successfully!");
echo json_encode($response);
mysqli_close($conn);
