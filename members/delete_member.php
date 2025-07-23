<?php
require '../includes/db.php';

$id = $_GET['id'];
$stmt = $conn->prepare("DELETE FROM members WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: manage_members.php");
?>
