<?php

$hostname = "127.0.0.1";
$user = "root.Att";
$password = "root";
$database = "banco_01_10";

$conexao = new mysqli($hostname, $user, $password, $database);

if ($conexao -> connect_errno) {
    echo "Failed to connect to MySQL: " . $conexao -> connect_error;
    exit();
} else {
    $nome = $conexao -> real_escape_string($_POST['nome']);
    $cpf = $conexao -> real_escape_string($_POST['cpf']);
    $data_nascimento = $conexao -> real_escape_string($_POST['data_nascimento']);

    $sql = "INSERT INTO `cliente` (`nome`, `cpf`, `data_nascimento`) VALUES ('".$nome."', '".$cpf."', '".$data_nascimento."')";

    $resultado = $conexao->query($sql);

    if ($resultado) {
        echo "Cliente cadastrado com sucesso!";
    } else {
        echo "Erro ao cadastrar o cliente: " . $conexao->error;
    }

    $conexao -> close();
    exit();
}

