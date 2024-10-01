<?php
session_start();

$hostname = "127.0.0.1";
$user = "root.Att";
$password = "root";
$database = "banco_01_10";

$conexao = new mysqli($hostname, $user, $password, $database);

if ($conexao->connect_error) {
    die("Falha na conexão: " . $conexao->connect_error);
}

$erroLogin = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Nome = $conexao->real_escape_string($_POST['Nome']);
    $email = $conexao->real_escape_string($_POST['email']);
    $senha = $conexao->real_escape_string($_POST['senha']);

    $sql = "SELECT * FROM gerente WHERE Nome = '$Nome' AND Email = '$email' AND senha = '$senha'";
    $result = $conexao->query($sql);

    if ($result && $result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $_SESSION['nome'] = $row['Nome']; 
        $_SESSION['email'] = $row['Email'];

        header("Location: telaGerente.php");
        exit();
    } else {
        $erroLogin = "Nome, email ou senha inválidos";
    }
}

$conexao->close();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Login</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/style.css">
        <script src="js/script.js" defer></script>
    </head>
    <body>
        <div class="container">
            <form action="" method="post">
                <div class="login">
                    <h1>LOGIN</h1>
                    <label style="margin-top:20px" for="nome">Nome:</label>
                    <input id="nome" type="text" name="Nome" placeholder="Digite seu Nome" required>
                    <label for="email">Email:</label>
                    <input id="email" type="email" name="email" placeholder="Digite seu Email" required>
                    <label for="senha">Senha:</label>
                    <div class="password-container">
                        <input type="password" id="password" name="senha" placeholder="Digite sua senha" required>
                        <span id="toggle-password" class="toggle-password">👁️</span>
                    </div>
                    <button type="submit">Enviar</button>
                    
                    <?php if (!empty($erroLogin)) : ?>
                        <div class="error-message" style="color:red; margin-top: 10px;">
                            <?= $erroLogin ?>
                        </div>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </body>
</html>
