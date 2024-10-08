<?php
// Configurações do banco de dados
$hostname = "127.0.0.1";
$user = "root.Att"; // Ajuste conforme necessário
$password = "root"; // Ajuste conforme necessário
$database = "banco_01_10"; // Ajuste conforme necessário

// Conexão com o banco de dados
$conn = new mysqli($hostname, $user, $password, $database);

// Verifica a conexão
if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Falha na conexão: ' . $conn->connect_error]));
}

// Recebe os dados do formulário
$id_cliente = $_POST['id_cliente'];
$endereco = $_POST['endereco'];

// Verificar se já existe um cartão cadastrado para o cliente
$query = "SELECT * FROM cartoes WHERE id_cliente = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_cliente);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Se já existe, retorne um erro
    echo json_encode(['success' => false, 'message' => 'Já existe um cartão cadastrado para este cliente.']);
} else {
    // Se não existe, prosseguir com a inserção
    $insertQuery = "INSERT INTO cartoes (id_cliente, endereco) VALUES (?, ?)";
    $insertStmt = $conn->prepare($insertQuery);
    $insertStmt->bind_param("is", $id_cliente, $endereco);
    
    if ($insertStmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao adicionar cartão.']);
    }
}

$stmt->close();
$conn->close();
?>