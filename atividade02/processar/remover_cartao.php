<?php
// Inclua aqui sua conexão com o banco de dados
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
$id_cliente = $_POST['id_cliente'];

// Remover o cartão do cliente
$deleteQuery = "DELETE FROM cartoes WHERE id_cliente = ?";
$stmt = $conn->prepare($deleteQuery);
$stmt->bind_param("i", $id_cliente);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Cartão removido com sucesso.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao remover cartão.']);
}

$stmt->close();
$conn->close();
?>
