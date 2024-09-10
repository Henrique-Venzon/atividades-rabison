<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/idade-nome.css">
        <script src="js/efeito.js" defer></script>
    <script src="https://unpkg.com/typed.js@2.1.0/dist/typed.umd.js"></script>
    </head>
    <body>
        <div class="container">
        <form action="" method="post">
        <div class="login">
        <h1 id="element"></h1>
        <label style="margin-top:80px" for="nome">Nome:</label>
        <input id="nome" type="text" name="nome">
        <label for="idade">Idade:</label>
        <input id="idade" type="number" name="idade">
        <button type="submit">Enviar</button>
        <h5 style="display:none;">Cadastrado!</h5>
        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $hostname = "127.0.0.1";
                    $user = "root.Att";
                    $password = "root";
                    $database = "atividade-rabison";

                    $conexao = new mysqli($hostname, $user, $password, $database);

                    if ($conexao -> connect_errno) {
                        echo "<p>Falha na conexão com MySQL: " . $conexao -> connect_error . "</p>";
                    } else {
                        $nome = $conexao -> real_escape_string($_POST['nome']);
                        $idade = $conexao -> real_escape_string($_POST['idade']);

                        $sql = "INSERT INTO `idade-nome` (`nome`, `idade`) VALUES ('".$nome."', '".$idade."')";

                        if ($conexao->query($sql) === TRUE) {
                            echo '<h5 >Cadastrado com sucesso!</h5>';
                        }

                        $conexao -> close();
            }
        }
            ?>
        </div>
        </form>
        </div>
        
        
    </body>
</html>