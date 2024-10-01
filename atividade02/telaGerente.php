<?php
session_start();

if (!isset($_SESSION['nome'])) {
    header("Location: atividade02.php"); 
    exit();
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Banco - Gerente</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/tela-gerente.css">
        <script src="https://kit.fontawesome.com/6934df05fc.js" crossorigin="anonymous"></script>
    </head>
    <body>
        <header>
            <h1 class="banco-h1">BANCO</h1>
            <?php
            echo '<h1 class="nome">' . htmlspecialchars($_SESSION['nome']) . '</h1>'; // Acesso correto à sessão
            ?>
            <i class="fa-solid fa-person-running"></i>
        </header>
        <script src="" async defer></script>
    </body>
</html>
