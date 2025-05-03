<?php
$logFile = __DIR__ . '/logs/gateway.log';

if (!file_exists($logFile)) {
    die("Log file not found.");
}

$lines = file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

$totalRequests = 0;
$perApiKey = [];
$statusCounts = [];

foreach ($lines as $line) {
    $totalRequests++;

    preg_match('/API Key: (.*?) - Path/', $line, $keyMatch);
    $apiKey = $keyMatch[1] ?? 'unknown';

    preg_match('/Status: (\d+)/', $line, $statusMatch);
    $status = $statusMatch[1] ?? 'unknown';

    $perApiKey[$apiKey] = ($perApiKey[$apiKey] ?? 0) + 1;
    $statusCounts[$status] = ($statusCounts[$status] ?? 0) + 1;
}


echo "<h2> API Gateway Log Analysis</h2>";
echo "<p><strong>Total Requests:</strong> $totalRequests</p>";

echo "<h3>Requests per API Key:</h3><ul>";
foreach ($perApiKey as $key => $count) {
    echo "<li><strong>$key:</strong> $count requests</li>";
}
echo "</ul>";

echo "<h3>Status Code Counts:</h3><ul>";
foreach ($statusCounts as $code => $count) {
    echo "<li><strong>$code:</strong> $count times</li>";
}
echo "</ul>";
?>
