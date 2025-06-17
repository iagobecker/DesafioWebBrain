<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Plataforma de Chamados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .card {
            width: 100%;
            max-width: 400px;
        }
    </style>
</head>

<body>
    <div class="card shadow">
        <div class="card-header bg-primary text-white text-center">
            <h3>Login do Sistema</h3>
        </div>
        <div class="card-body">
            <div id="login-messages" class="mb-3"></div>
            <form id="form-login">
                <div class="mb-3">
                    <label for="email" class="form-label">E-mail</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="senha" class="form-label">Senha</label>
                    <input type="password" class="form-control" id="senha" name="senha" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Entrar</button>
                <div class="text-center mt-3">
                    <a href="cadastro.php">Não tem uma conta? Cadastre-se</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#form-login').on('submit', function(e) {
                e.preventDefault();
                const loginMessages = $('#login-messages');
                loginMessages.html('').removeClass('alert alert-danger');

                $.ajax({
                    url: 'backend/auth/processa_login.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            window.location.href = response.redirect;
                        } else {
                            loginMessages.html(response.message).addClass('alert alert-danger');
                        }
                    },
                    error: function() {
                        loginMessages.html('Erro ao conectar com o servidor.').addClass('alert alert-danger');
                    }
                });
            });
        });
    </script>
</body>

</html>