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
    }

    // 入室
    public function join($user)
    {
        $this->users[$user->address] = $user;
        echo("join: {$user->address} room_id: {$this->id}\n");
        echo("room count: " . count($this->users) . "\n");
    }

    // 退室
    public function leave($user)
    {
        unset($this->users[$user->address]);
    }

    // メッセージ送信
    public function send($address, $message)
    {
        foreach ($this->users as $user) {
            if ($user->address !== $address) {
                $user->send($message);
            }
        }
    }
}