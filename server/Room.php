<?php
spl_autoload_register(function ($class_name) {
  include $class_name . '.php';
});

class Room
{
  // ルームID
  private $id;

  // 入室しているユーザ
  private $users = [];

  // constractor
  public function __construct($room_id)
  {
    $this->id = $room_id;
    echo('Room #' . $this->id . ' created.' . PHP_EOL);
  }

  // 入室
  public function join($user)
  {
    $this->users[$user->address] = $user;
    $user->send("result", "Joined room #{$this->id}");
    echo("[join] {$user->address} joined into #{$this->id}\n");
    echo(count($this->users) . " users in room #{$this->id}.\n");

  }

  // 退室
  public function leave($user)
  {
    unset($this->users[$user->address]);
    $user->send("result", "Left room #{$this->id}.");
    echo("[leave] {$user->address} left from #{$this->id}\n");
    echo(count($this->users) . " users in room #{$this->id}.\n");
  }

  // メッセージ送信
  public function send($address, $message)
  {
    $user = null;
    foreach ($this->users as $peer_user) {
      if ($peer_user->address !== $address) {
        $peer_user->send("message", $message);
        echo("[send] $address sent '$message' to {$peer_user->address}\n");
      }
      else{
        $user = $peer_user;
      }
    }
    $user->send("result", "Sent '$message' to #{$this->id}.");
  }
}
