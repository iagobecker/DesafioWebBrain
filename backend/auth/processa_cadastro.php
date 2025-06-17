<?php
require_once '../db_connect.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'Ocorreu um erro.'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome_completo = trim($_POST['nome_completo']);
    $data_nascimento = trim($_POST['data_nascimento']);
    $email = trim($_POST['email']);
    $telefone = trim($_POST['telefone']);
    $whatsapp = trim($_POST['whatsapp']);
    $estado = trim($_POST['estado']);
    $cidade = trim($_POST['cidade']);
    $senha = trim($_POST['senha']);
    $confirma_senha = trim($_POST['confirma_senha']);

    if (empty($nome_completo) || empty($data_nascimento) || empty($email) || empty($whatsapp) || empty($estado) || empty($cidade) || empty($senha)) {
        $response['message'] = "Todos os campos obrigatórios devem ser preenchidos.";
        echo json_encode($response);
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response['message'] = "Formato de e-mail inválido.";
        echo json_encode($response);
        exit();
    }

    if ($senha !== $confirma_senha) {
        $response['message'] = "As senhas não coincidem.";
        echo json_encode($response);
        exit();
    }

    $hoje = new DateTime();
    $nascimento = new DateTime($data_nascimento);
    $idade = $hoje->diff($nascimento)->y;
    if ($idade < 18) {
        $response['message'] = "O usuário deve ter mais de 18 anos.";
        echo json_encode($response);
        exit();
    }

    $sql_check = "SELECT id FROM usuarios WHERE email = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        $response['message'] = "Este e-mail já está cadastrado.";
        $stmt_check->close();
        $conn->close();
        echo json_encode($response);
        exit();
    }
    $stmt_check->close();

    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    $sql_insert = "INSERT INTO usuarios (nome_completo, data_nascimento, email, telefone, whatsapp, estado, cidade, senha) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);

    $stmt_insert->bind_param("ssssssss", $nome_completo, $data_nascimento, $email, $telefone, $whatsapp, $estado, $cidade, $senha_hash);

    if ($stmt_insert->execute()) {
        $response['success'] = true;
        $response['message'] = "Cadastro realizado com sucesso! Você já pode fazer login.";
    } else {
        $response['message'] = "Erro ao realizar o cadastro. Tente novamente.";
    }

    $stmt_insert->close();
    $conn->close();
} else {
    $response['message'] = "Método de requisição inválido.";
}

echo json_encode($response);
