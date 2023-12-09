<?php
session_start();
/** Inicia um sessão */
include_once 'db.php';
/** Inclui o arquivo com a conexão ao banco de dados */

/** Recebe os dados do formulário via POST */
$dados = filter_input_array(INPUT_POST);

/**
 * Verifica se é uma exclusão (delete) de um evento
 * Efetua a exclusão se verdadeiro e salva uma mensagem na sessão msg
 * Salva uma mensagem de erro na sessão msg, caso ocorra algum erro
 */
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

/** Caso não seja uma exclusão, exclui o campo action do array $dados */
unset($dados['action']);

/** Define os campos obrigatórios do formulário */
$required = ['title', 'ev_start'];

/** Traduz o nome dos campos, caso precisem ser apresentados ao usuário */
$translate = ['title' => 'Nome do Evento', 'ev_start' => 'Início'];

/** Verifica se o usuário preencheu as datas corretamente */
if ($dados['ev_start'] >= $dados['ev_end']) {
    $_SESSION['msg'] = "<p class='alert-danger'>Erro! O final do evento não pode ser igual ou anterior ao início.</p>";
    header("Location: index.php");
    exit;
}

/**
 * Verifica se existe algum campo obrigatório vazio, e informa o usuário
 * Se todos os campos obrigatórios estiverem preenchidos cria um array $set com os campos para o SET da query
 * do MySql, com o nome do campo = :campo
 */
foreach ($dados as $key => $item) {
    if (in_array($key, $required) && empty($item)) {
        $_SESSION['msg'] = "<p class='alert-danger'>Erro! O campo " . $translate[$key] . " é obrigatório.</p>";
        header("Location: index.php");
        exit;
    }

    $set[] = $key . ' = :' . $key;
}
/** Transforma o array em uma string */
$set = implode(', ', $set);

/** Verifica se não existe um id, se não existir insere os dados no banco de dados */
if (!$dados['id']) {
    $sql = $db->prepare("INSERT INTO events SET $set");
    foreach ($dados as $key => $item) {
        $sql->bindValue(":" . $key, $item);
    }
    $sql->execute();
    $_SESSION['msg'] = "<p class='alert-success'>Ok! Evento cadastrado com sucesso.</p>";
    header("Location: index.php");
} else {
    /** Caso exista um id, faz um update dos dados do banco de dados */
    $sql = $db->prepare("UPDATE events SET $set WHERE id = :id");
    foreach ($dados as $key => $item) {
        $sql->bindValue(":" . $key, $item);
    }
    $sql->execute();
    $_SESSION['msg'] = "<p class='alert-success'>Ok! Evento editado com sucesso.</p>";
    header("Location: index.php");
}
