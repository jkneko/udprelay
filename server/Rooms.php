<?php
spl_autoload_register(function ($class_name) {
  include $class_name . '.php';
});

class Rooms
{
  // 全ルーム
  private $rooms = [];

  // ルームを作成して入室
  public function join($room_id, $address, $socket)
  {
    $room = $this->get_room($room_id);
    $user = new User($address, $socket);
    $room->join($user);
  }

  // 該当のルームから退室
  public function leave($room_id, $address)
  {
    $room = $this->get_room($room_id);
    $room->leave($address);
  }

  // 該当のルームにメッセージを送信
  public function send($room_id, $message, $address)
  {
    $room = $this->get_room($room_id);
    $room->send($address, $message);
  }

  // ルームを取得。なければ作成
  private function get_room($id)
  {
    if (!isset($this->rooms[$id])) {
      $this->rooms[$id] = new Room($id);
    }
    return $this->rooms[$id];
  }
}