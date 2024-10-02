<?php
session_start();

if (!isset($_SESSION['nome'])) {
    header("Location: atividade02.php");
    exit();
}

$mensagem = '';  
$clientes = [];  

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['acao']) && $_POST['acao'] === 'remover_cliente') {
        $idCliente = $_POST['id_cliente'];

        $hostname = "127.0.0.1";
        $user = "root.Att";
        $password = "root";
        $database = "banco_01_10";

        $conexao = new mysqli($hostname, $user, $password, $database);

        if ($conexao->connect_errno) {
            echo json_encode(['success' => false, 'message' => 'Falha ao conectar ao MySQL: ' . $conexao->connect_error]);
            exit();
        }

        $sql = "DELETE FROM cliente WHERE id_cliente = '$idCliente'";

        if ($conexao->query($sql)) {
            echo json_encode(['success' => true, 'message' => 'Cliente removido com sucesso!']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao remover o cliente: ' . $conexao->error]);
        }

        $conexao->close();
        exit(); 
    } else {
        $hostname = "127.0.0.1";
        $user = "root.Att";
        $password = "root";
        $database = "banco_01_10";

        $conexao = new mysqli($hostname, $user, $password, $database);

        if ($conexao->connect_errno) {
            $mensagem = "Failed to connect to MySQL: " . $conexao->connect_error;
        } else {
            $nome = $conexao->real_escape_string($_POST['nome']);
            $cpf = $conexao->real_escape_string($_POST['cpf']);
            $data_nascimento = $conexao->real_escape_string($_POST['data_nascimento']);

            $sql = "SELECT COUNT(*) as total FROM cliente WHERE cpf = '$cpf'";
            $resultado = $conexao->query($sql);
            $row = $resultado->fetch_assoc();

            if ($row['total'] > 0) {
                $mensagem = "Erro: CPF já cadastrado!";
            } else {
                $sql = "INSERT INTO `cliente` (`nome`, `cpf`, `data_nascimento`) VALUES ('$nome', '$cpf', '$data_nascimento')";
                if ($conexao->query($sql)) {
                    $mensagem = "Cliente cadastrado com sucesso!";
                } else {
                    $mensagem = "Erro ao cadastrar o cliente: " . $conexao->error;
                }
            }

            $conexao->close();
        }
    }
}

$hostname = "127.0.0.1";
$user = "root.Att";
$password = "root";
$database = "banco_01_10";

$conexao = new mysqli($hostname, $user, $password, $database);

if ($conexao->connect_errno) {
    $mensagem = "Erro ao conectar ao MySQL: " . $conexao->connect_error;
} else {
    $sql = "SELECT id_cliente, nome, cpf, data_nascimento FROM cliente";
    $resultado = $conexao->query($sql);

    if ($resultado->num_rows > 0) {
        while ($cliente = $resultado->fetch_assoc()) {
            $clientes[] = $cliente;
        }
    } else {
        $mensagem = "Nenhum cliente encontrado.";
    }

    $conexao->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Banco - Gerente</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/tela-gerente.css">
    <script src="https://kit.fontawesome.com/6934df05fc.js" crossorigin="anonymous"></script>

</head>
<body>
<header>
    <h1 class="banco-h1">BANCO</h1>
    <?php
    echo '<h1 class="nome">' . htmlspecialchars($_SESSION['nome']) . '</h1>';
    ?>
    <i class="fa-solid fa-person-running" id="sair"></i>
</header>

<div class="container">
    <div class="caixa-button">
        <button class="button-cl" id="btnCadastrar">Cadastrar cliente</button>
        <button class="button-cl" id="btnListar">Listar cliente</button>
    </div>

    <div class="container-conteudo">
        <div class="caixa-cadastrar" id="cadastrarCliente" style="display:none;">
            <h1 class="titulo">Cadastrar clientes</h1>
            <form action="" method="post">
                <label for="cliente">Nome cliente:</label>
                <input type="text" id="cliente" name="nome" required>
                <label for="cpf">CPF:</label>
                <input type="text" id="cpf" name="cpf" maxlength="11" minlength="11" pattern="\d{11}" title="O CPF deve conter 11 dígitos" required>
                <label for="date">Data de nascimento:</label>
                <input type="date" id="date" name="data_nascimento" required>
                <button type="submit">ENVIAR</button>
            </form>
            <?php if (!empty($mensagem)): ?>
                <p class="<?php echo (strpos($mensagem, 'sucesso') !== false) ? 'mensagem-sucesso' : 'mensagem-erro'; ?>">
                    <?php echo $mensagem; ?>
                </p>
            <?php endif; ?>
        </div>

        <div class="caixa-listagem-de-clientes" id="listarCliente" style="display:none;">
            <div class="caixa-listagem">
                <h1 class="titulo2">Listagem de clientes</h1>
                <?php if (!empty($clientes)): ?>
                    <div class="tabela-max">
                        <table>
                            <thead>
                                <tr>
                                    <th style="border-left:none;border-top:none;">ID Cliente</th>
                                    <th style="border-top:none;">Nome</th>
                                    <th style="border-top:none;">CPF</th>
                                    <th style="border-top:none;">Data de Nascimento</th>
                                    <th style="border-top:none;">Visualizar</th>
                                    <th style="border-top:none;">Remover</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-clientes">
                                <?php foreach ($clientes as $cliente): ?>
                                    <tr data-id="<?php echo $cliente['id_cliente']; ?>">
                                        <td style="border-left:none;"><?php echo $cliente['id_cliente']; ?></td>
                                        <td><?php echo htmlspecialchars($cliente['nome']); ?></td>
                                        <td><?php echo htmlspecialchars($cliente['cpf']); ?></td>
                                        <td><?php echo htmlspecialchars($cliente['data_nascimento']); ?></td>
                                        <td>
                                            <button class="btn-visualizar" data-id="<?php echo $cliente['id_cliente']; ?>">Ver</button>
                                        </td>
                                        <td>
                                            <button class="btn-remover" data-id="<?php echo $cliente['id_cliente']; ?>">Remover</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="nenhum-cliente">Nenhum cliente encontrado.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Div para visualizar detalhes do cliente -->
        <div class="caixa-visualizar" id="visualizarCliente">
            <h1 class="titulo">Visualizar cliente</h1>
            <p><strong>ID:</strong> <span id="visualizar-id"></span></p>
            <p><strong>Nome:</strong> <span id="visualizar-nome"></span></p>
            <p><strong>CPF:</strong> <span id="visualizar-cpf"></span></p>
            <p><strong>Data de Nascimento:</strong> <span id="visualizar-data-nascimento"></span></p>
            <button id="btnFechar">Fechar</button>
        </div>
    </div>
</div>

<!-- Scripts -->
<script>
    // Eventos dos botões de cadastrar e listar
    document.getElementById('btnCadastrar').addEventListener('click', function() {
        document.getElementById('cadastrarCliente').style.display = 'block';
        document.getElementById('listarCliente').style.display = 'none';
        document.getElementById('visualizarCliente').style.display = 'none';
    });

    document.getElementById('btnListar').addEventListener('click', function() {
        document.getElementById('listarCliente').style.display = 'block';
        document.getElementById('cadastrarCliente').style.display = 'none';
        document.getElementById('visualizarCliente').style.display = 'none';
    });

    // Evento para visualizar detalhes do cliente
    document.querySelectorAll('.btn-visualizar').forEach(function(button) {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const row = this.closest('tr');
            const nome = row.children[1].textContent;
            const cpf = row.children[2].textContent;
            const dataNascimento = row.children[3].textContent;

            document.getElementById('visualizar-id').textContent = id;
            document.getElementById('visualizar-nome').textContent = nome;
            document.getElementById('visualizar-cpf').textContent = cpf;
            document.getElementById('visualizar-data-nascimento').textContent = dataNascimento;

            document.getElementById('visualizarCliente').style.display = 'block';
            document.getElementById('listarCliente').style.display = 'none';
        });
    });

    // Evento para remover cliente
    document.querySelectorAll('.btn-remover').forEach(function(button) {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');

            if (confirm('Tem certeza que deseja remover este cliente?')) {
                fetch('', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: 'acao=remover_cliente&id_cliente=' + id
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        // Remove a linha da tabela
                        const row = this.closest('tr');
                        row.remove();
                    } else {
                        alert(data.message);
                    }
                });
            }
        });
    });

    // Evento para fechar a visualização do cliente
    document.getElementById('btnFechar').addEventListener('click', function() {
        document.getElementById('visualizarCliente').style.display = 'none';
        document.getElementById('listarCliente').style.display = 'block';
    });


</script>
<script src="js/sair.js"></script>
</body>
</html>
