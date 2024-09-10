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
        <form action="processos/idade-nome.php" method="post">
        <div class="login">
        <h1 id="element"></h1>
        <label style="margin-top:80px" for="nome">Nome:</label>
        <input id="nome" type="text" name="nome">
        <label for="idade">Idade:</label>
        <input id="idade" type="number" name="idade">
        <button type="submut">Enviar</button>
        </div>
        </form>
        </div>
        
        
    </body>
</html>