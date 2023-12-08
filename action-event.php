<?php
session_start();
include_once 'db.php';

$dados = filter_input_array(INPUT_POST);

if (!empty($dados['action']) && $dados['action'] == 'delete') {
    $sql = $db->prepare("DELETE FROM events WHERE id = :id");
    $sql->bindValue(":id", $dados['id']);
    if (!$sql->execute()) {
        $_SESSION['msg'] = "<p class='alert-danger'>Erro! Evento não existente ou já excluído. Atualize a página.</p>";
        header("Location: index.php");
        exit;
    }
    $_SESSION['msg'] = "<p class='alert-success'>Ok! Evento excluído com sucesso.</p>";
    header("Location: index.php");
    exit;
}

unset($dados['action']);

$required = ['title', 'ev_start'];
$translate = ['title' => 'Nome do Evento', 'ev_start' => 'Início'];

if ($dados['ev_start'] >= $dados['ev_end']) {
    $_SESSION['msg'] = "<p class='alert-danger'>Erro! O final do evento não pode ser igual ou anterior ao início.</p>";
    header("Location: index.php");
    exit;
}

foreach ($dados as $key => $item) {
    if (in_array($key, $required) && empty($item)) {
        $_SESSION['msg'] = "<p class='alert-danger'>Erro! O campo " . $translate[$key] . " é obrigatório.</p>";
        header("Location: index.php");
        exit;
    }

    $set[] = $key . ' = :' . $key;
}
$set = implode(', ', $set);

if (!$dados['id']) {
    $sql = $db->prepare("INSERT INTO events SET $set");
    foreach ($dados as $key => $item) {
        $sql->bindValue(":" . $key, $item);
    }
    $sql->execute();
    $_SESSION['msg'] = "<p class='alert-success'>Ok! Evento cadastrado com sucesso.</p>";
    header("Location: index.php");
} else {
    $sql = $db->prepare("UPDATE events SET $set WHERE id = :id");
    foreach ($dados as $key => $item) {
        $sql->bindValue(":" . $key, $item);
    }
    $sql->execute();
    $_SESSION['msg'] = "<p class='alert-success'>Ok! Evento editado com sucesso.</p>";
    header("Location: index.php");
}
