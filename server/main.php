<?php
spl_autoload_register(function ($class_name) {
  include $class_name . '.php';
});

const RPC_METHOD_JOIN = "join";
const RPC_METHOD_LEAVE = "leave";
const RPC_METHOD_SEND = "send";

// help
if($argc != 3){
  echo <<< EOL
usage: php main.php [address] [port]

    address      : Listenするアドレス
    port         : Listenするポート番号

EOL;
  exit(1);
}

// パラメータ
$listen_address = $argv[1];
$listen_port = $argv[2];

// ルーム
$rooms = new Rooms();

// サーバソケット作成
$socket = stream_socket_server("udp://$listen_address:$listen_port", $errno, $errstr, STREAM_SERVER_BIND);
if (!$socket) {
  die("$errstr ($errno)");
}
echo('Start to listen.' . PHP_EOL);

// 受信ループ
do {
  $json = stream_socket_recvfrom($socket, 1500, 0, $address);
  echo($json);
  $data = json_decode($json);
  if(!$data){
    echo("invalid json: $json\n");
    $json = json_encode(['error' => "invalid json: $json"]);
    stream_socket_sendto($socket, $json, 0, $address);
    continue;
  }
  switch($data->method){
    case RPC_METHOD_JOIN:
      $rooms->join($data->params->room_id, $address, $socket);
      break;
    case RPC_METHOD_LEAVE:
      $rooms->leave($data->params->room_id, $address);
      break;
    case RPC_METHOD_SEND:
      $rooms->send($data->params->room_id, $data->params->message, $address);
      break;
  }
} while ($json !== false);

