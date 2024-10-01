<?php
$hostname = "127.0.0.1";
$user = "root.Att";
$password = "root";
$database = "banco_01_10";

$conexao = new mysqli($hostname, $user, $password, $database);

if ($conexao->connect_error) {
    die("Falha na conexão: " . $conexao->connect_error);
}

$erroLogin = ""; // Variável para armazenar a mensagem de erro

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conexao->real_escape_string($_POST['email']);
    $senha = $conexao->real_escape_string($_POST['senha']);

    $sql = "SELECT * FROM gerente WHERE Email = '$email' AND senha = '$senha'";
    $result = $conexao->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        session_start();
        $_SESSION['email'] = $row['Email'];
        
        // Redirecionar para telaGerente.php
        header("Location: ../telaGerente.php");
        exit();
    } else {
        $erroLogin = "Email ou senha inválidos";
    }
}

$conexao->close();
?>
