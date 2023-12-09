<?php
include_once 'db.php';
/** Inclui o arquivo com a conexão ao banco de dados */

/** Cria um array vazio */
$evs = [];

/** Busca os eventos cadastrados no banco de dados */
$sql = $db->query("SELECT id, title, color, ev_start, ev_end FROM events");
$events = $sql->fetchAll();

/**
 * Percorre todos os eventos e armazena os dados necessários no array $evs
 * Retorna um json com os dados para o calendário
 */
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
