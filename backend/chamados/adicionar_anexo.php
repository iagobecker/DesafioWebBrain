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
    $usuario_id = $_SESSION['usuario_id'];
    $nome_usuario = $_SESSION['usuario_nome'];

    if ($chamado_id <= 0 || empty($_POST['anexos_base64'])) {
        $response['message'] = 'Nenhum anexo ou chamado especificado.';
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

    $conn->begin_transaction();
    try {
        $sql_anexo = "INSERT INTO chamados_anexos (chamado_id, nome_arquivo, arquivo) VALUES (?, ?, ?)";
        $stmt_anexo = $conn->prepare($sql_anexo);

        $sql_historico = "INSERT INTO chamados_historico (chamado_id, usuario_id, descricao) VALUES (?, ?, ?)";
        $stmt_historico = $conn->prepare($sql_historico);

        foreach ($_POST['anexos_base64'] as $key => $base64_data) {
            $nome_arquivo = $_POST['anexos_nomes'][$key] ?? 'arquivo_sem_nome';

            $stmt_anexo->bind_param("iss", $chamado_id, $nome_arquivo, $base64_data);
            $stmt_anexo->execute();

            $descricao_historico = $nome_usuario . " adicionou um novo anexo: " . htmlspecialchars($nome_arquivo);
            $stmt_historico->bind_param("iis", $chamado_id, $usuario_id, $descricao_historico);
            $stmt_historico->execute();
        }

        $stmt_anexo->close();
        $stmt_historico->close();

        $conn->commit();
        $response['success'] = true;
        $response['message'] = 'Anexos adicionados com sucesso.';
    } catch (Exception $e) {
        $conn->rollback();
        $response['message'] = 'Erro ao salvar os anexos: ' . $e->getMessage();
    }

    $conn->close();
}

echo json_encode($response);
