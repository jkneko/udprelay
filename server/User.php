<?php
spl_autoload_register(function ($class_name) {
  include $class_name . '.php';
});

class User
{
  public $address;
  private $socket;

  // constractor
  public function __construct($address, $socket)
  {
    $this->address = $address;
    $this->socket = $socket;
  }

  public function send($message)
  {
    $json = json_encode(['jsonrpc' => 2.0, 'method' => 'send', 'params' => $message]);
    stream_socket_sendto($this->socket, $json, 0, $this->address);
    echo("send: $json to: {$this->address}\n");
  }
}