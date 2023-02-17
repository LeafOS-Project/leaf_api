<?php

header("Content-Type: application/json");

if (isset($_GET['device']) and isset($_GET['flavor'])) {
	$device = $_GET['device'];
	$flavor = $_GET['flavor'];
	$incremental = isset($_GET['incremental']) ? $_GET['incremental'] : '';

	$mysqli = new mysqli("localhost", "leaf", "leaf", "leaf_ota");
	if ($mysqli->connect_errno) {
		die("Database unavailable!");
	}

	$stmt = $mysqli->prepare("SELECT * FROM leaf_ota WHERE device = ? AND flavor = ? AND (incremental_base = ? OR incremental_base is NULL) ORDER BY incremental DESC");
	$stmt->bind_param('sss', $device, $flavor, $incremental);
	$stmt->execute();

	$result = $stmt->get_result();

	$json = [];
	$json['response'] = [];

	while ($row = $result->fetch_assoc()) {
		array_push($json['response'], array_filter($row));
	}

	echo json_encode($json, JSON_PRETTY_PRINT);
}

?>
