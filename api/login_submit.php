<?php
session_start();
require("../includes/database_connect.php");

$email = $_POST['email'];
$password_input = $_POST['password'];

$stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE email=?");
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result) {
    $response = array("success" => false, "message" => "Something went wrong!");
    echo json_encode($response);
    return;
}

$row_count = mysqli_num_rows($result);
if ($row_count == 0) {
    $response = array("success" => false, "message" => "Login failed! Invalid email or password.");
    echo json_encode($response);
    return;
}

$row = mysqli_fetch_assoc($result);

if (password_verify($password_input, $row['password']) || sha1($password_input) === $row['password']) {
    $_SESSION['user_id'] = $row['id'];
    $_SESSION['full_name'] = $row['full_name'];
    $_SESSION['email'] = $row['email'];

    $response = array("success" => true, "message" => "Login successful!");
} else {
    $response = array("success" => false, "message" => "Login failed! Invalid email or password.");
    echo json_encode($response);
    return;
}
echo json_encode($response);
mysqli_close($conn);
