<?php

$hostname = "127.0.0.1";
$user = "root.Att";
$password = "root";
$database = "atividade-rabison";

$conexao = new mysqli($hostname, $user, $password, $database);

if ($conexao -> connect_errno) {
echo "Failed to connect to MySQL: " . $conexao -> connect_error;
exit();
} else {
// Evita caracteres especiais (SQL Inject)
$nome = $conexao -> real_escape_string($_POST['nome']);
$idade = $conexao -> real_escape_string($_POST['idade']);

                $sql="INSERT INTO `idade-nome`
(`nome`, `idade`)
VALUES
('".$nome."', '".$idade."')";

$resultado = $conexao->query($sql);

$conexao -> close();
                echo 'Cadastrado com sucesso.';
exit();
}