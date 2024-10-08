<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['nome'])) {
    header("Location: atividade02.php");
    exit();
}

$mensagem = '';  
$clientes = [];  

// Configurações do banco de dados
$hostname = "127.0.0.1";
$user = "root.Att"; // Ajuste conforme necessário
$password = "root"; // Ajuste conforme necessário
$database = "banco_01_10"; // Ajuste conforme necessário

// Conexão com o banco de dados
$conexao = new mysqli($hostname, $user, $password, $database);
if ($conexao->connect_errno) {
    $mensagem = "Erro ao conectar ao MySQL: " . $conexao->connect_error;
    exit();
}

// Cadastra um novo cliente
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nome'])) {
    $nome = $conexao->real_escape_string($_POST['nome']);
    $cpf = $conexao->real_escape_string($_POST['cpf']);
    $data_nascimento = $conexao->real_escape_string($_POST['data_nascimento']);
    
    $sql = "INSERT INTO cliente (nome, cpf, data_nascimento) VALUES ('$nome', '$cpf', '$data_nascimento')";
    
    if ($conexao->query($sql)) {
        $mensagem = "Cliente cadastrado com sucesso!";
    } else {
        $mensagem = "Erro ao cadastrar cliente: " . $conexao->error;
    }
}

// Remover cliente ou cartão
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['acao'])) {
    $acao = $_POST['acao'];
    $idCliente = $_POST['id_cliente'];

    if ($acao === 'remover_cliente') {
        $sql = "DELETE FROM cliente WHERE id_cliente = '$idCliente'";
        if ($conexao->query($sql)) {
            echo json_encode(['success' => true, 'message' => 'Cliente removido com sucesso!']);
            exit();
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao remover o cliente: ' . $conexao->error]);
            exit();
        }
    } elseif ($acao === 'remover_cartao') {
        $sql = "DELETE FROM cartao WHERE id_cartao = '$idCliente'"; // Corrigido: id_cartao
        if ($conexao->query($sql)) {
            echo json_encode(['success' => true, 'message' => 'Cartão removido com sucesso!']);
            exit();
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao remover o cartão: ' . $conexao->error]);
            exit();
        }
    } elseif ($acao === 'adicionar_cartao') {
        $endereco = $conexao->real_escape_string($_POST['endereco']);
        $sql = "INSERT INTO cartao (id_cliente, endereco) VALUES ('$idCliente', '$endereco')"; // Corrigido: id_cliente
        if ($conexao->query($sql)) {
            echo json_encode(['success' => true, 'message' => 'Cartão adicionado com sucesso!']);
            exit();
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao adicionar o cartão: ' . $conexao->error]);
            exit();
        }
    }
}

// Listar clientes
$sql = "SELECT id_cliente, nome, cpf, data_nascimento FROM cliente";
$resultado = $conexao->query($sql);
if ($resultado->num_rows > 0) {
    while ($cliente = $resultado->fetch_assoc()) {
        $clientes[] = $cliente;
    }
}

// Fechar conexão
$conexao->close();
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
                                    <th>ID Cliente</th>
                                    <th>Nome</th>
                                    <th>CPF</th>
                                    <th>Data de Nascimento</th>
                                    <th>Visualizar</th>
                                    <th>Remover</th>
                                </tr>
                            </thead>
                            <tbody id="tbody-clientes">
                                <?php foreach ($clientes as $cliente): ?>
                                    <tr data-id="<?php echo $cliente['id_cliente']; ?>">
                                        <td><?php echo $cliente['id_cliente']; ?></td>
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

        <div class="caixa-visualizar" id="visualizarCliente" style="display:none;">
            <h1 class="titulo-3">Visualizar cliente</h1>
            <p class="tabela-p"><strong>ID:</strong> <span id="visualizar-id"></span></p>
            <p class="tabela-p"><strong>Nome:</strong> <span id="visualizar-nome"></span></p>
            <p class="tabela-p"><strong>CPF:</strong> <span id="visualizar-cpf"></span></p>
            <p class="tabela-p"><strong>Data de Nascimento:</strong> <span id="visualizar-data-nascimento"></span></p>
            <div class="buttonfechar">
                <button id="btnAdicionarCartao" style="display: none;">Adicionar cartão</button>
                <button id="botaoRemoverCartao" style="display: none;">Remover Cartão</button>
                <button id="btnFechar">Fechar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para adicionar cartão -->
<div id="modalCartao" style="display:none;">
    <div class="modal-content" onclick="event.stopPropagation();">
        <span id="btnFecharModal" style="cursor: pointer; float: right;">&times;</span>
        <h2>Adicionar Cartão</h2>
        <form id="formAdicionarCartao">
            <label for="endereco">Endereço:</label>
            <input type="text" id="endereco" name="endereco" required>
            <input type="hidden" id="idClienteCartao" name="id_cliente">
            <button type="submit">Criar</button>
        </form>
    </div>
</div>



<script>
document.getElementById('btnCadastrar').addEventListener('click', function() {
    document.getElementById('cadastrarCliente').style.display = 'block';
    document.getElementById('listarCliente').style.display = 'none';
    document.getElementById('visualizarCliente').style.display = 'none'; // Adicione esta linha
});

document.getElementById('btnListar').addEventListener('click', function() {
    document.getElementById('listarCliente').style.display = 'block';
    document.getElementById('cadastrarCliente').style.display = 'none';
        document.getElementById('visualizarCliente').style.display = 'none'; // Adicione esta linha

});

// Mostrar modal para adicionar cartão
document.getElementById('btnAdicionarCartao').addEventListener('click', function() {
    document.getElementById('modalCartao').style.display = 'block';
    document.getElementById('idClienteCartao').value = document.getElementById('visualizar-id').textContent;
});

// Fechar modal
document.getElementById('btnFechar').addEventListener('click', function() {
    document.getElementById('visualizarCliente').style.display = 'none';
    document.getElementById('listarCliente').style.display = 'block';
    document.getElementById('modalCartao').style.display = 'none';
});

// Visualizar cliente
// Visualizar cliente
document.querySelectorAll('.btn-visualizar').forEach(button => {
    button.addEventListener('click', function() {
        const id = this.getAttribute('data-id');
        const row = this.closest('tr');
        const nome = row.cells[1].textContent;
        const cpf = row.cells[2].textContent;
        const dataNascimento = row.cells[3].textContent;

        document.getElementById('visualizar-id').textContent = id;
        document.getElementById('visualizar-nome').textContent = nome;
        document.getElementById('visualizar-cpf').textContent = cpf;
        document.getElementById('visualizar-data-nascimento').textContent = dataNascimento;

        // Verifica se o cliente já possui um cartão
        fetch('verifica_cartao.php?id_cliente=' + id)
            .then(response => response.json())
            .then(data => {
                if (data.tem_cartao) {
                    document.getElementById('btnRemoverCartao').style.display = 'block';
                    document.getElementById('btnAdicionarCartao').style.display = 'none'; // Ocultar o botão
                } else {
                    document.getElementById('btnAdicionarCartao').style.display = 'block'; // Mostrar o botão
                    document.getElementById('btnRemoverCartao').style.display = 'none'; // Ocultar o botão
                }
            });

        document.getElementById('listarCliente').style.display = 'none';
        document.getElementById('visualizarCliente').style.display = 'block';

        // Adicione o evento de clique aqui
        document.getElementById('btnAdicionarCartao').addEventListener('click', function() {
            document.getElementById('modalCartao').style.display = 'block';
            document.getElementById('idClienteCartao').value = id; // Defina o ID do cliente no campo oculto
        });
    });
});
// Fechar modal ao clicar no botão de fechar
document.getElementById('btnFecharModal').addEventListener('click', function() {
    document.getElementById('modalCartao').style.display = 'none';
});

// Fechar modal ao clicar fora do conteúdo
document.getElementById('modalCartao').addEventListener('click', function(event) {
    if (event.target === this) {
        this.style.display = 'none';
    }
});
// Função para adicionar cartão
document.getElementById('formAdicionarCartao').addEventListener('submit', function(event) {
    event.preventDefault(); // Impede o envio padrão do formulário

    // Coleta os dados do formulário
    const idCliente = document.getElementById('idClienteCartao').value;
    const endereco = document.getElementById('endereco').value;

    // Envio dos dados para o PHP via fetch
    fetch('processar/salvar_cartao.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            'id_cliente': idCliente,
            'endereco': endereco
        })
    })
    .then(response => response.json())
    .then(data => {
        console.log(data);
        if (data.success) {
            alert('Cartão adicionado com sucesso!');
            document.getElementById('modalCartao').style.display = 'none'; // Fecha o modal
        } else {
            alert('Erro ao adicionar cartão: ' + data.message);
            // Aqui você pode ocultar o botão de adicionar e mostrar o de remover
            document.getElementById('botaoAdicionarCartao').style.display = 'none';
            document.getElementById('botaoRemoverCartao').style.display = 'block';
        }
    })
    .catch(error => console.error('Erro ao enviar dados:', error));
});


document.getElementById('botaoRemoverCartao').addEventListener('click', function() {
    const idCliente = document.getElementById('idClienteCartao').value;

    fetch('processar/remover_cartao.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            'id_cliente': idCliente
        })
    })
    .then(response => response.json())
    .then(data => {
        console.log(data);
        if (data.success) {
            alert(data.message);
            // Oculta o botão de remover e mostra o de adicionar
            document.getElementById('botaoRemoverCartao').style.display = 'none';
            document.getElementById('botaoAdicionarCartao').style.display = 'block';
        } else {
            alert('Erro ao remover cartão: ' + data.message);
        }
    })
    .catch(error => console.error('Erro ao remover cartão:', error));
});


// Remover cliente
document.querySelectorAll('.btn-remover').forEach(button => {
    button.addEventListener('click', function() {
        const id = this.getAttribute('data-id');

        if (confirm('Tem certeza que deseja remover este cliente?')) {
            const formData = new FormData();
            formData.append('acao', 'remover_cliente');
            formData.append('id_cliente', id);

            fetch('', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.success) {
                    location.reload();
                }
            });
        }
    });
});


</script>
</body>
</html>
