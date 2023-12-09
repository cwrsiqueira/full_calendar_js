<?php

/** dados de conexão com o banco de dados */
$host = 'localhost';
$dbname = 'full_calendar';
$user = 'root';
$pass = '';

/** Conecta ao banco de dados ou mostra uma mensagem de erro */
try {
    $db = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    // echo "Banco de dados conectado com sucesso.";
} catch (PDOException $e) {
    echo $e->getMessage();
}
