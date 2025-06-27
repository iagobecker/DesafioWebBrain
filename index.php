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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }

        .navbar {
            transition: background-color 0.3s ease-in-out;
        }

        .hero-section {
            background: linear-gradient(135deg, #0d6efd 0%, #052c65 100%);
            color: white;
            padding: 80px 0;
            display: flex;
            align-items: center;
            min-height: 85vh;
        }
        
        .hero-section h1 {
            font-weight: 700; 
            font-size: 3.2rem;
        }
        
        .hero-section .lead {
            font-size: 1.2rem;
            opacity: 0.9;
            margin: 20px 0 30px;
        }

        .hero-section .btn {
            padding: 12px 30px;
            font-weight: 600;
            border-radius: 50px; 
            margin: 5px;
            transition: all 0.3s ease;
        }
        
        .hero-section .btn-light {
            color: #0d6efd;
        }
        
        .hero-section .btn-outline-light:hover {
            background-color: white;
            color: #0d6efd;
        }

        .hero-image {
            max-width: 100%;
            border-radius: 15px;
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
        }

        .features-section {
            padding: 80px 0;
        }
        
        .feature-icon {
            width: 64px;
            height: 64px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background-color: #e7f1ff;
            color: #0d6efd;
            border-radius: 50%;
            margin-bottom: 20px;
        }
        
        footer {
            background-color: #343a40;
            color: white;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <strong>Prefeitura |</strong> Chamados TI
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="btn btn-outline-primary me-2" href="login.php">Fazer Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary" href="cadastro.php">Cadastrar-se</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <header class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4">Otimize a Gestão de TI da Prefeitura</h1>
                    <p class="lead">
                        Uma plataforma moderna e eficiente para registrar problemas técnicos, 
                        solicitar suporte e acompanhar o status dos seus chamados em tempo real.
                    </p>
                    <a href="cadastro.php" class="btn btn-light">Comece Agora</a>
                    <a href="login.php" class="btn btn-outline-light">Já Tenho Conta</a>
                </div>
                <div class="col-lg-6 text-center mt-4 mt-lg-0">
                    <img src="https://www.valuehost.com.br/blog/wp-content/uploads/2021/06/post_thumbnail-dd717dc4e16a2f3353a07b48a291be86-770x515.jpeg.webp" alt="Ilustração do sistema de chamados" class="hero-image">
                </div>
            </div>
        </div>
    </header>

    <section class="features-section">
        <div class="container">
            <h2 class="text-center mb-5">Nossos Diferenciais</h2>
            <div class="row text-center">
                <div class="col-md-4">
                    <div class="feature-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-speedometer2" viewBox="0 0 16 16"><path d="M8 4a.5.5 0 0 1 .5.5V6a.5.5 0 0 1-1 0V4.5A.5.5 0 0 1 8 4M3.732 5.732a.5.5 0 0 1 .707 0l.915.914a.5.5 0 1 1-.708.708l-.914-.915a.5.5 0 0 1 0-.707M12.268 5.732a.5.5 0 0 1 0 .707l-.914.915a.5.5 0 0 1-.708-.708l.915-.914a.5.5 0 0 1 .707 0m-6.463 5.09a.5.5 0 0 1 .708 0l.914-.915a.5.5 0 1 1 .707.708l-.915.914a.5.5 0 0 1-.707 0zm8.66-5.09a.5.5 0 0 1 0 .707L14.25 7.9a.5.5 0 0 1-.707-.707l.914-.915a.5.5 0 0 1 .707 0M11.5 14a.5.5 0 0 1-.5.5h-6a.5.5 0 0 1 0-1h6a.5.5 0 0 1 .5.5M4.5 12a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5m0-2a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5m0-2a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5"/></svg>
                    </div>
                    <h3>Agilidade</h3>
                    <p>Abra chamados em poucos minutos, de qualquer lugar, e receba notificações sobre o andamento.</p>
                </div>
                <div class="col-md-4">
                    <div class="feature-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-card-checklist" viewBox="0 0 16 16"><path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5zM15 3a1.5 1.5 0 0 0-1.5-1.5h-13A1.5 1.5 0 0 0 0 3v9a1.5 1.5 0 0 0 1.5 1.5h13A1.5 1.5 0 0 0 15 12z"/><path d="M1.5 4h13a.5.5 0 0 1 0 1h-13a.5.5 0 0 1 0-1m0 3h13a.5.5 0 0 1 0 1h-13a.5.5 0 0 1 0-1m0 3h13a.5.5 0 0 1 0 1h-13a.5.5 0 0 1 0-1"/><path d="M3.854 8.146a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 1 1 .708-.708L2.5 9.293l1.146-1.147a.5.5 0 0 1 .708 0M3.854 11.146a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 0 1 .708-.708L2.5 12.293l1.146-1.147a.5.5 0 0 1 .708 0"/></svg>
                    </div>
                    <h3>Transparência</h3>
                    <p>Acompanhe o histórico completo e o status do seu chamado em tempo real, sem surpresas.</p>
                </div>
                <div class="col-md-4">
                    <div class="feature-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-archive" viewBox="0 0 16 16"><path d="M0 2a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1v7.5a2.5 2.5 0 0 1-2.5 2.5h-9A2.5 2.5 0 0 1 1 12.5V5a1 1 0 0 1-1-1zm2 3v7.5A1.5 1.5 0 0 0 3.5 14h9a1.5 1.5 0 0 0 1.5-1.5V5zm13-3H1v2h14zM5 7.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5"/></svg>
                    </div>
                    <h3>Organização</h3>
                    <p>Anexe arquivos, adicione contatos e mantenha todas as informações centralizadas em um só lugar.</p>
                </div>
            </div>
        </div>
    </section>

    <footer class="text-center p-4">
        <div class="container">
            <p class="mb-0">&copy; <?php echo date('Y'); ?> Prefeitura Municipal. Todos os direitos reservados.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>