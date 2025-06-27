<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php';

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

    $stmt_check = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    if ($stmt_check->get_result()->num_rows > 0) {
        $response['message'] = "Este e-mail já está cadastrado.";
        echo json_encode($response);
        exit();
    }

    $codigo_validacao = md5(uniqid(rand(), true));

    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    $sql_insert = "INSERT INTO usuarios (nome_completo, data_nascimento, email, telefone, whatsapp, estado, cidade, senha, codigo_validacao) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("sssssssss", $nome_completo, $data_nascimento, $email, $telefone, $whatsapp, $estado, $cidade, $senha_hash, $codigo_validacao);

    if ($stmt_insert->execute()) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'iagosbm97@gmail.com';
            $mail->Password   = 'ikjh rshp dktk kmea';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom('iagosbm97@gmail.com', 'Prefeitura - Chamados de TI');
            $mail->addAddress($email, $nome_completo);

            $mail->isHTML(true);
            $mail->Subject = 'Valide seu cadastro no Sistema de Chamados';
            $link_validacao = "http://localhost/prefeitura_chamados/validar_email.php?codigo=" . $codigo_validacao;
            $mail->Body    = "Olá, $nome_completo!<br><br>Obrigado por se cadastrar. Por favor, clique no link abaixo para validar seu e-mail e ativar sua conta:<br><br><a href='$link_validacao'>Validar Meu E-mail</a><br><br>Se você não se cadastrou, por favor ignore este e-mail.<br><br>Atenciosamente,<br>Equipe de TI da Prefeitura";
            $mail->AltBody = "Olá, $nome_completo!\n\nObrigado por se cadastrar. Por favor, copie e cole o seguinte link no seu navegador para validar seu e-mail e ativar sua conta:\n$link_validacao\n\nAtenciosamente,\nEquipe de TI da Prefeitura";

            $mail->send();

            $response['success'] = true;
            $response['message'] = 'Cadastro realizado com sucesso! Um e-mail de validação foi enviado para sua caixa de entrada.';
        } catch (Exception $e) {
            $response['success'] = true;
            $response['message'] = "Cadastro realizado, mas houve um erro ao enviar o e-mail de validação. Erro: {$mail->ErrorInfo}";
        }
    } else {
        $response['message'] = "Erro ao realizar o cadastro. Tente novamente.";
    }

    $stmt_insert->close();
    $conn->close();
}

echo json_encode($response);
