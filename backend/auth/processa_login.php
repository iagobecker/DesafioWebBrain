<?php
session_start();

require_once '../db_connect.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'E-mail ou senha inválidos.'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);

    if (empty($email) || empty($senha)) {
        $response['message'] = "Por favor, preencha e-mail e senha.";
        echo json_encode($response);
        exit();
    }

    $sql = "SELECT id, nome_completo, senha FROM usuarios WHERE email = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $usuario = $result->fetch_assoc();

        if (password_verify($senha, $usuario['senha'])) {
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome_completo'];

            $response['success'] = true;
            $response['message'] = 'Login bem-sucedido!';
            $response['redirect'] = 'painel.php';
        }
    }

    $stmt->close();
    $conn->close();
}

echo json_encode($response);
