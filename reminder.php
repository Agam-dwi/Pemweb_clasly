<?php
session_start();
include 'service/database.php';

header('Content-Type: application/json');

if (!isset($_SESSION['is_login'])) {
    echo json_encode([]);
    exit;
}

$id_pengguna = $_SESSION['is_login'];
$stmt = $db->prepare("SELECT hari, TIME_FORMAT(jam, '%H:%i') as jam, matakuliah FROM jadwal WHERE id_pengguna = ?");
$stmt->bind_param("i", $id_pengguna);
$stmt->execute();
$result = $stmt->get_result();

$jadwals = [];
while ($row = $result->fetch_assoc()) {
    $jadwals[] = $row;
}

echo json_encode($jadwals);
