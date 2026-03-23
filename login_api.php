<?php
header('Content-Type: application/json');
include("../db.php"); // db.php should be in C:\xampp\htdocs\safeher\db.php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success'=>false,'message'=>'Use POST']);
    exit;
}

$email = isset($_POST['email']) ? $conn->real_escape_string($_POST['email']) : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

if (!$email || !$password) {
    echo json_encode(['success'=>false,'message'=>'Missing fields']);
    exit;
}

$sql = "SELECT * FROM users WHERE email='$email' LIMIT 1";
$res = $conn->query($sql);
if ($res && $res->num_rows > 0) {
    $row = $res->fetch_assoc();
    if (password_verify($password, $row['password'])) {
        echo json_encode(['success'=>true,'user'=>['id'=>$row['id'],'name'=>$row['name'],'email'=>$row['email']]]);
    } else {
        echo json_encode(['success'=>false,'message'=>'Invalid credentials']);
    }
} else {
    echo json_encode(['success'=>false,'message'=>'User not found']);
}
