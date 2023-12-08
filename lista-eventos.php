<?php
include_once 'db.php';

$evs = [];
$sql = $db->query("SELECT id, title, color, ev_start, ev_end FROM events");
$events = $sql->fetchAll();

foreach ($events as $e) {
    extract($e);

    // if (explode(' ', $ev_start)[0] < date('Y-m-d')) {
    //     $color = 'red';
    // } elseif (explode(' ', $ev_start)[0] == date('Y-m-d')) {
    //     $color = 'orange';
    // } else {
    //     $color = 'green';
    // }

    $evs[] = [
        'id' => $id,
        'title' => $title,
        'color' => $color,
        'start' => $ev_start,
        'end' => $ev_end,
    ];
}

echo json_encode($evs);
