<?php
header('Content-Type: application/json');

$db = "play_integrity";
$table = "play_integrity";
$mysqli = new mysqli("localhost", "leaf", "leaf", $db);
if ($mysqli->connect_errno) {
    http_response_code(500);
    die("Database unavailable!");
}

$headers = getallheaders();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && strpos($_SERVER['REQUEST_URI'], '/update') !== false) {
    if (isset($headers['Api-Key'])) {
        if ($headers['Api-Key'] !== $_SERVER['LEAF_PLAY_API_KEY']) {
            http_response_code(401);
            echo json_encode(['message' => 'Invalid API key']);
            exit;
        }
    } else {
        http_response_code(400);
        echo json_encode(['message' => 'API key is missing']);
        exit;
    }

    $data = json_decode(file_get_contents('php://input'), true);

    $brand = $data['BRAND'];
    $manufacturer = $data['MANUFACTURER'];
    $model = $data['MODEL'];
    $product = $data['PRODUCT'];
    $device = $data['DEVICE'];
    $id = $data['ID'];
    $fingerprint = $data['FINGERPRINT'];
    $security_patch = $data['VERSION:SECURITY_PATCH'];

    $mysqli->query("DELETE FROM $table");
    $stmt = $mysqli->prepare("INSERT INTO $table (BRAND, MANUFACTURER, MODEL, PRODUCT, DEVICE, ID, FINGERPRINT, `VERSION:SECURITY_PATCH`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $brand, $manufacturer, $model, $product, $device, $id, $fingerprint, $security_patch);
    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(['message' => 'Data inserted']);
    } else {
        http_response_code(500);
        echo json_encode(['message' => 'Failed to insert data']);
    }
} else {
    $stmt = $mysqli->prepare("SELECT * FROM $table");
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        http_response_code(200);
        $row = $result->fetch_assoc();
        $filteredRow = array_filter($row, function ($value) {
            return $value !== null;
        });
        echo json_encode($filteredRow, JSON_PRETTY_PRINT);
    } else {
        http_response_code(404);
        echo json_encode(['message' => 'No data found']);
    }
}
