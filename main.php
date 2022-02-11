<?php
spl_autoload_register(function ($class_name) {
  include $class_name . '.php';
});

const RPC_METHOD_JOIN = "join";
const RPC_METHOD_LEAVE = "leave";
const RPC_METHOD_SEND = "send";

// ルーム
$rooms = new Rooms();

// サーバソケット作成
$socket = stream_socket_server("udp://10.138.0.4:9999", $errno, $errstr, STREAM_SERVER_BIND);
if (!$socket) {
    die("$errstr ($errno)");
}

// 受信ループ
do {
    $json = stream_socket_recvfrom($socket, 1500, 0, $address);
    $data = json_decode($json);
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

