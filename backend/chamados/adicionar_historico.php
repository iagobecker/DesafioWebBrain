<?php
session_start();
header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'Ocorreu um erro.'];

if (!isset($_SESSION['usuario_id'])) {
    $response['message'] = 'Acesso negado. Faça login.';
    echo json_encode($response);
    exit();
}

require_once '../db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $chamado_id = isset($_POST['chamado_id']) ? (int)$_POST['chamado_id'] : 0;
    $nova_descricao = trim($_POST['nova_descricao'] ?? '');
    $usuario_id = $_SESSION['usuario_id'];
    $nome_usuario = $_SESSION['usuario_nome'];

    if ($chamado_id <= 0 || empty($nova_descricao)) {
        $response['message'] = 'Dados inválidos fornecidos.';
        echo json_encode($response);
        exit();
    }

    $stmt_check = $conn->prepare("SELECT id FROM chamados WHERE id = ? AND usuario_id = ?");
    $stmt_check->bind_param("ii", $chamado_id, $usuario_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows !== 1) {
        $response['message'] = 'Acesso negado a este chamado.';
        echo json_encode($response);
        $stmt_check->close();
        $conn->close();
        exit();
    }
    $stmt_check->close();

    $descricao_final = $nome_usuario . " adicionou uma nova interação: " . $nova_descricao;

    $sql = "INSERT INTO chamados_historico (chamado_id, usuario_id, descricao) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $chamado_id, $usuario_id, $descricao_final);

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Interação adicionada com sucesso.';
    } else {
        $response['message'] = 'Erro ao salvar a interação.';
    }

    $stmt->close();
    $conn->close();
}

echo json_encode($response);
