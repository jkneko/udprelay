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
        echo("[join] {$user->address} joined into #{$this->id}\n");
        echo(count($this->users) . " users in room #{$this->id}.\n");
    }

    // 退室
    public function leave($user)
    {
        unset($this->users[$user->address]);
        echo("[leave] {$user->address} left from #{$this->id}\n");
        echo(count($this->users) . " users in room #{$this->id}.\n");
    }

    // メッセージ送信
    public function send($address, $message)
    {
        foreach ($this->users as $user) {
            if ($user->address !== $address) {
                $user->send($message);
                echo("[send] $address sent '$message' to {$user->address}\n");
            }
        }
    }
}
