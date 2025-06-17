<?php
session_start();

if (isset($_SESSION['usuario_id'])) {
    header('Location: painel.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bem-vindo à Plataforma de Chamados de TI</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .hero {
            background: linear-gradient(45deg, rgba(0, 123, 255, 0.7), rgba(0, 69, 203, 0.8)), url('https://images.unsplash.com/photo-1558021211-6d140f9de831?q=80&w=1935') no-repeat center center;
            background-size: cover;
            color: white;
            padding: 100px 0;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="hero">
        <div class="container">
            <h1 class="display-4">Plataforma de Chamados de TI</h1>
            <p class="lead">O canal direto entre os funcionários da prefeitura e a equipe de TI.</p>
            <hr class="my-4">
            <p>Registre problemas técnicos, sugestões ou incidentes de forma rápida e eficiente.</p>
            <a class="btn btn-primary btn-lg" href="login.php" role="button">Fazer Login</a>
            <a class="btn btn-success btn-lg" href="cadastro.php" role="button">Cadastrar-se</a>
        </div>
    </div>

    <div class="container mt-5 mb-5">
        <div class="row text-center">
            <div class="col-md-4">
                <h3><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-check-circle-fill text-primary" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                    </svg> Agilidade</h3>
                <p>Abra chamados em poucos minutos, de qualquer lugar.</p>
            </div>
            <div class="col-md-4">
                <h3><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-chat-left-dots-fill text-primary" viewBox="0 0 16 16">
                        <path d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H4.414a1 1 0 0 0-.707.293L.854 15.146A.5.5 0 0 1 0 14.793zM5 6a1 1 0 1 0-2 0 1 1 0 0 0 2 0m4 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0m3 1a1 1 0 1 0 0-2 1 1 0 0 0 0 2" />
                    </svg> Transparência</h3>
                <p>Acompanhe o histórico e o status do seu chamado em tempo real.</p>
            </div>
            <div class="col-md-4">
                <h3><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-file-earmark-arrow-up-fill text-primary" viewBox="0 0 16 16">
                        <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0M9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1M6.354 9.854a.5.5 0 0 1-.708-.708l2-2a.5.5 0 0 1 .708 0l2 2a.5.5 0 0 1-.708.708L8.5 8.207V12.5a.5.5 0 0 1-1 0V8.207z" />
                    </svg> Organização</h3>
                <p>Anexe arquivos e adicione informações a qualquer momento.</p>
            </div>
        </div>
    </div>

    <footer class="text-center p-4 bg-light">
        <p>&copy; <?php echo date('Y'); ?> Prefeitura Municipal. Todos os direitos reservados.</p>
    </footer>

    <script src="assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>