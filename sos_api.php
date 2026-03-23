<?php
header('Content-Type: application/json');
include("../db.php");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success'=>false,'message'=>'Use POST']);
    exit;
}

$user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
$latitude = isset($_POST['latitude']) ? $conn->real_escape_string($_POST['latitude']) : '';
$longitude = isset($_POST['longitude']) ? $conn->real_escape_string($_POST['longitude']) : '';

if (!$user_id || $latitude === '' || $longitude === '') {
    echo json_encode(['success'=>false,'message'=>'Missing fields']);
    exit;
}

$sql = "INSERT INTO alerts (user_id, latitude, longitude) VALUES ('$user_id', '$latitude', '$longitude')";
if ($conn->query($sql) === TRUE) {
    echo json_encode(['success'=>true,'message'=>'Alert saved']);
} else {
    echo json_encode(['success'=>false,'message'=>$conn->error]);
}
