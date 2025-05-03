<?php
$pdo = new PDO('mysql:host=localhost;dbname=apigateway_db', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$request_path = $_GET['request_path'] ?? '';
$headers = getallheaders();
$client_ip = $_SERVER['REMOTE_ADDR'];
$api_key = $headers['X-API-Key'] ?? null;
$status_code = 200;


$user = null;
if (!$api_key) {
    $status_code = 401;
    echo json_encode(["error" => "Missing API Key"]);
    logRequest();
    http_response_code($status_code);
    exit;
}

$stmt = $pdo->prepare("SELECT user_name FROM api_keys WHERE api_key = ?");
$stmt->execute([$api_key]);
$user = $stmt->fetchColumn();

if (!$user) {
    $status_code = 401;
    echo json_encode(["error" => "Invalid API Key"]);
    logRequest();
    http_response_code($status_code);
    exit;
}


$limit = 10;
$window = 60; 
$current_time = time();

$stmt = $pdo->prepare("SELECT last_request_ts, request_count FROM rate_limits WHERE api_key = ?");
$stmt->execute([$api_key]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if ($data) {
    $elapsed = $current_time - $data['last_request_ts'];

    if ($elapsed > $window) {
        $stmt = $pdo->prepare("UPDATE rate_limits SET last_request_ts = ?, request_count = 1 WHERE api_key = ?");
        $stmt->execute([$current_time, $api_key]);
    } else {
        if ($data['request_count'] >= $limit) {
            $status_code = 429;
            echo json_encode(["error" => "Rate limit exceeded"]);
            logRequest();
            http_response_code($status_code);
            exit;
        } else {
            $stmt = $pdo->prepare("UPDATE rate_limits SET request_count = request_count + 1 WHERE api_key = ?");
            $stmt->execute([$api_key]);
        }
    }
} else {
    $stmt = $pdo->prepare("INSERT INTO rate_limits (api_key, last_request_ts, request_count) VALUES (?, ?, 1)");
    $stmt->execute([$api_key, $current_time]);
}


$response = null;
$forwarded_header = "X-Forwarded-By: MyPHPGateway";
$base_dir = __DIR__ . "/services";

switch ($request_path) {
    case "users":
        $response = includeService("service_users.php");
        break;
    case "products":
        $response = includeService("service_products.php");
        break;
    case "dashboard":
        $users = json_decode(includeService("service_users.php"), true);
        $products = json_decode(includeService("service_products.php"), true);
        $response = json_encode([
            "users" => $users,
            "products" => $products
        ]);
        break;
    default:
        $status_code = 404;
        $response = json_encode(["error" => "Unknown endpoint"]);
}

http_response_code($status_code);
header('Content-Type: application/json');
echo $response;
logRequest();

function includeService($file) {
    global $base_dir;
    header("X-Forwarded-By: MyPHPGateway");
    ob_start();
    include "$base_dir/$file";
    return ob_get_clean();
}

function logRequest() {
    global $client_ip, $api_key, $request_path, $status_code;
    $log = "[" . date("Y-m-d H:i:s") . "] - IP: $client_ip - API Key: " . ($api_key ?? 'None') . " - Path: $request_path - Status: $status_code\n";
    file_put_contents(__DIR__ . "/logs/gateway.log", $log, FILE_APPEND);
}
?>
