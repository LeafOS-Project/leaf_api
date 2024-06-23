<?php

header("Content-Type: application/json");

$includes = ["https://dl.google.com/developers/android/gsi/gsi-src.json"];
$images = [];

// Available devices
$devices = [];
$devices['leaf_gsi_arm64'] = [];
$devices['leaf_gsi_arm64']['cpu_abi'] = "arm64-v8a";
$devices['leaf_gsi_arm64']['name'] = "LeafOS on ARM64";

$mysqli = new mysqli("localhost", "leaf", "leaf", "leaf_ota");
if ($mysqli->connect_errno) {
	die("Database unavailable!");
}

foreach ($devices as $codename => $device) {
	$stmt = $mysqli->prepare("SELECT DISTINCT(version) FROM leaf_ota WHERE device = ?");
	$stmt->bind_param('s', $codename);
	$stmt->execute();

	$result = $stmt->get_result();

	while ($row = $result->fetch_assoc()) {
		$stmt = $mysqli->prepare("SELECT DISTINCT(flavor) FROM leaf_ota WHERE device = ? AND version = ?");
		$stmt->bind_param('ss', $codename, $row['version']);
		$stmt->execute();

		$flavor_result = $stmt->get_result();

		while ($flavor_row = $flavor_result->fetch_assoc()) {
			$stmt = $mysqli->prepare("SELECT filename, url FROM leaf_ota WHERE device = ? AND version = ? AND flavor = ? ORDER BY datetime DESC LIMIT 1");
			$stmt->bind_param('sss', $codename, $row['version'], $flavor_row['flavor']);
			$stmt->execute();

			$build_result = $stmt->get_result();
			while ($build = $build_result->fetch_assoc()) {
				$image = [];
				$image['name'] = $device['name'] . " (" . $flavor_row['flavor'] . ")";
				$image['cpu_abi'] = $device['cpu_abi'];
				$image['details'] = $build['filename'];
				$image['uri'] = $build['url'];
				array_push($images, $image);
			}
		}
	}
}

$json = [];
$json["include"] = $includes;
$json["images"] = $images;

echo json_encode($json, JSON_PRETTY_PRINT);

?>
