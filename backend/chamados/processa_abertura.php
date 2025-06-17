<?php
session_start();
header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'Ocorreu um erro inesperado.'];

if (!isset($_SESSION['usuario_id'])) {
    $response['message'] = 'Acesso negado. Faça login novamente.';
    echo json_encode($response);
    exit();
}

require_once '../db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario_id = $_SESSION['usuario_id'];
    $tipo_incidente = trim($_POST['tipo_incidente'] ?? '');
    $descricao_problema = trim($_POST['descricao_problema'] ?? '');

    if (empty($tipo_incidente) || empty($descricao_problema)) {
        $response['message'] = 'Tipo de incidente e descrição são obrigatórios.';
        echo json_encode($response);
        exit();
    }

    $conn->begin_transaction();

    try {
        $sql_chamado = "INSERT INTO chamados (usuario_id, tipo_incidente, descricao_problema, status) VALUES (?, ?, ?, 'Aberto')";
        $stmt_chamado = $conn->prepare($sql_chamado);
        $stmt_chamado->bind_param("iss", $usuario_id, $tipo_incidente, $descricao_problema);
        $stmt_chamado->execute();

        $chamado_id = $conn->insert_id;
        $stmt_chamado->close();

        if (!empty($_POST['contato_nome'])) {
            $sql_contato = "INSERT INTO chamados_contatos (chamado_id, nome_contato, telefone_contato, observacao) VALUES (?, ?, ?, ?)";
            $stmt_contato = $conn->prepare($sql_contato);

            foreach ($_POST['contato_nome'] as $key => $nome) {
                $telefone = $_POST['contato_telefone'][$key] ?? '';
                $obs = $_POST['contato_obs'][$key] ?? '';
                $stmt_contato->bind_param("isss", $chamado_id, $nome, $telefone, $obs);
                $stmt_contato->execute();
            }
            $stmt_contato->close();
        }

        if (!empty($_POST['anexos_base64'])) {
            $sql_anexo = "INSERT INTO chamados_anexos (chamado_id, nome_arquivo, arquivo) VALUES (?, ?, ?)";
            $stmt_anexo = $conn->prepare($sql_anexo);

            foreach ($_POST['anexos_base64'] as $key => $base64_data) {
                $nome_arquivo = $_POST['anexos_nomes'][$key] ?? 'anexo_sem_nome.txt';
                $stmt_anexo->bind_param("iss", $chamado_id, $nome_arquivo, $base64_data);
                $stmt_anexo->execute();
            }
            $stmt_anexo->close();
        }

        $nome_usuario = $_SESSION['usuario_nome'];
        $descricao_historico = "Chamado aberto por " . $nome_usuario . ".";

        $sql_historico = "INSERT INTO chamados_historico (chamado_id, usuario_id, descricao) VALUES (?, ?, ?)";
        $stmt_historico = $conn->prepare($sql_historico);
        $stmt_historico->bind_param("iis", $chamado_id, $usuario_id, $descricao_historico);
        $stmt_historico->execute();
        $stmt_historico->close();

        $conn->commit();
        $response['success'] = true;
        $response['message'] = 'Chamado aberto com sucesso!';
    } catch (Exception $e) {
        $conn->rollback();
        $response['message'] = 'Erro ao salvar o chamado: ' . $e->getMessage();
    }
} else {
    $response['message'] = 'Método de requisição inválido.';
}

$conn->close();
echo json_encode($response);
