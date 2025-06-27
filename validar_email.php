<?php
require_once 'backend/db_connect.php';

$message = "Código de validação inválido ou o e-mail já foi validado.";
$alert_type = "danger";

if (isset($_GET['codigo']) && !empty($_GET['codigo'])) {
    $codigo = trim($_GET['codigo']);

    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE codigo_validacao = ? AND email_validado = 0");
    $stmt->bind_param("s", $codigo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $stmt_update = $conn->prepare("UPDATE usuarios SET email_validado = 1, codigo_validacao = NULL WHERE codigo_validacao = ?");
        $stmt_update->bind_param("s", $codigo);

        if ($stmt_update->execute() && $stmt_update->affected_rows > 0) {
            $message = "E-mail validado com sucesso! Você já pode fazer o login.";
            $alert_type = "success";
        } else {
            $message = "Ocorreu um erro ao validar seu e-mail ou este link já foi utilizado.";
        }
        $stmt_update->close();
    }
    $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validação de Cadastro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-6">
                <div class="card text-center">
                    <div class="card-body p-5">
                        <div class="alert alert-<?php echo $alert_type; ?>">
                            <?php echo htmlspecialchars($message); ?>
                        </div>
                        <a href="login.php" class="btn btn-primary">Ir para a Página de Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>