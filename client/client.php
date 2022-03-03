<?php

if($argc != 3){
	// 使い方表示
	echo <<< EOL
usage: php client.php [address] [port]

    address   : 接続先アドレス
    port      : 接続先ポート番号

command:
    join      : {"method": "join", "params":{"room_id": 1}}
    leave     : {"method": "leave", "params":{"room_id": 1}}
    send      : {"method": "send", "params":{"message": "hello", "room_id": 1}}
    keepalive : {"method": "keepalive"}

EOL;
	exit(1);
}

// パラメータ
$server_address = $argv[1];
$server_port = $argv[2];

// ソケット作成
$sock = stream_socket_client('udp://'.$server_address.':'.$server_port, $errno, $errstr, 30);

// ソケットも標準入力もブロックしない
stream_set_blocking($sock, false);
stream_set_blocking(STDIN, false);

while(true){
    // ソケットから得た情報を標準出力に送る
    $data = fread($sock, 1500);
    if($data){
        echo "received data: $data\n";
    }

    // 標準入力の情報をサーバに送る
    $line = fgets(STDIN);
    if($line){
        echo "send data: $line\n";
        fwrite($sock, $line);    
    }

    usleep(100000);
}
