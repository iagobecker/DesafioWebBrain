<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Plataforma de Chamados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .card {
            margin-top: 50px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h3>Formulário de Cadastro</h3>
                    </div>
                    <div class="card-body">
                        <div id="form-messages" class="mb-3"></div>
                        <form id="form-cadastro">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="nome_completo" class="form-label">Nome Completo</label>
                                    <input type="text" class="form-control" id="nome_completo" name="nome_completo" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="data_nascimento" class="form-label">Data de Nascimento</label>
                                    <input type="date" class="form-control" id="data_nascimento" name="data_nascimento" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">E-mail</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="telefone" class="form-label">Telefone</label>
                                    <input type="text" class="form-control" id="telefone" name="telefone" placeholder="(00) 0000-0000">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="whatsapp" class="form-label">WhatsApp</label>
                                    <input type="text" class="form-control" id="whatsapp" name="whatsapp" placeholder="(00) 00000-0000" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="estado" class="form-label">Estado</label>
                                    <select class="form-select" id="estado" name="estado" required>
                                        <option value="">Selecione um Estado</option>
                                        <option value="SP">São Paulo</option>
                                        <option value="RJ">Rio de Janeiro</option>
                                        <option value="MG">Minas Gerais</option>
                                        <option value="BA">Bahia</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="cidade" class="form-label">Cidade</label>
                                    <select class="form-select" id="cidade" name="cidade" required disabled>
                                        <option value="">Escolha um estado primeiro</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="senha" class="form-label">Senha</label>
                                    <input type="password" class="form-control" id="senha" name="senha" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="confirma_senha" class="form-label">Confirmação da Senha</label>
                                    <input type="password" class="form-control" id="confirma_senha" name="confirma_senha" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Cadastrar</button>
                            <div class="text-center mt-3">
                                <a href="login.php">Já tem uma conta? Faça login</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#telefone').mask('(00) 0000-0000');
            $('#whatsapp').mask('(00) 00000-0000');

            $('#estado').on('change', function() {
                const estado = $(this).val();
                const cidadeSelect = $('#cidade');

                cidadeSelect.prop('disabled', true).html('<option value="">Carregando...</option>');

                if (estado) {
                    $.ajax({
                        url: 'get_cidades.php',
                        type: 'GET',
                        data: {
                            estado: estado
                        },
                        dataType: 'json',
                        success: function(cidades) {
                            cidadeSelect.prop('disabled', false).html('<option value="">Selecione uma Cidade</option>');
                            if (cidades.length > 0) {
                                $.each(cidades, function(index, cidade) {
                                    cidadeSelect.append($('<option>', {
                                        value: cidade,
                                        text: cidade
                                    }));
                                });
                            } else {
                                cidadeSelect.html('<option value="">Nenhuma cidade encontrada</option>');
                            }
                        },
                        error: function() {
                            cidadeSelect.html('<option value="">Erro ao carregar cidades</option>');
                        }
                    });
                } else {
                    cidadeSelect.prop('disabled', true).html('<option value="">Escolha um estado primeiro</option>');
                }
            });

            $('#form-cadastro').on('submit', function(e) {
                e.preventDefault();

                const formMessages = $('#form-messages');
                formMessages.html('').removeClass('alert alert-danger alert-success');

                const senha = $('#senha').val();
                const confirmaSenha = $('#confirma_senha').val();
                const dataNascimento = $('#data_nascimento').val();

                if (senha !== confirmaSenha) {
                    formMessages.html('As senhas não coincidem.').addClass('alert alert-danger');
                    return;
                }

                if (dataNascimento) {
                    const hoje = new Date();
                    const nascimento = new Date(dataNascimento);
                    let idade = hoje.getFullYear() - nascimento.getFullYear();
                    const m = hoje.getMonth() - nascimento.getMonth();
                    if (m < 0 || (m === 0 && hoje.getDate() < nascimento.getDate())) {
                        idade--;
                    }
                    if (idade < 18) {
                        formMessages.html('Você deve ter mais de 18 anos para se cadastrar.').addClass('alert alert-danger');
                        return;
                    }
                }

                $.ajax({
                    url: 'backend/auth/processa_cadastro.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            formMessages.html(response.message).addClass('alert alert-success');
                            $('#form-cadastro')[0].reset();
                            $('#cidade').prop('disabled', true).html('<option value="">Escolha um estado primeiro</option>');
                        } else {
                            formMessages.html(response.message).addClass('alert alert-danger');
                        }
                    },
                    error: function() {
                        formMessages.html('Ocorreu um erro ao processar sua solicitação. Tente novamente.').addClass('alert alert-danger');
                    }
                });
            });
        });
    </script>
</body>

</html>