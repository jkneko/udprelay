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

  public function send($field, $message)
  {
    $json = json_encode([$field => $message]);
    stream_socket_sendto($this->socket, $json, 0, $this->address);
    echo("send: $json to: {$this->address}\n");
  }
}