<?php
require '../includes/db.php';

// Sanitize and format contact number
$contact = $_POST['contact_number'];
if (!str_starts_with($contact, '+63')) {
    $contact = '+63' . ltrim($contact, '0');
}

$stmt = $conn->prepare("INSERT INTO members (
    name, 
    address, 
    contact_number, 
    gender, 
    birthday,
    group_membership,
    role_type,
    officer_position
) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

$role_type = $_POST['role_type'];
$officer_position = ($role_type === 'officer') ? $_POST['officer_position'] : NULL;

$stmt->bind_param("ssssssss", 
    $_POST['name'],
    $_POST['address'],
    $contact,
    $_POST['gender'],
    $_POST['birthday'],
    $_POST['group_membership'],
    $role_type,
    $officer_position
);
$stmt->execute();

header("Location: manage_members.php");
?>
