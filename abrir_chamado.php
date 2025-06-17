<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Abrir Novo Chamado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h3>Registro de Novo Chamado</h3>
            </div>
            <div class="card-body">
                <div id="form-messages" class="mb-3"></div>
                <form id="form-abrir-chamado">
                    <div class="mb-3">
                        <label for="tipo_incidente" class="form-label">Tipo de Incidente</label>
                        <select id="tipo_incidente" name="tipo_incidente" class="form-select" required>
                            <option value="">Selecione...</option>
                            <option value="Problema de Hardware">Problema de Hardware</option>
                            <option value="Problema de Software">Problema de Software</option>
                            <option value="Problema de Rede">Problema de Rede</option>
                            <option value="Solicitação de Acesso">Solicitação de Acesso</option>
                            <option value="Outros">Outros</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="descricao_problema" class="form-label">Descrição do Problema</label>
                        <textarea id="descricao_problema" name="descricao_problema" required></textarea>
                    </div>

                    <hr>
                    <h5>Contatos Telefônicos Adicionais</h5>
                    <div id="contatos-container">
                    </div>
                    <button type="button" id="add-contato" class="btn btn-secondary btn-sm mb-3">Adicionar Contato</button>

                    <hr>
                    <h5>Anexos (1 ou mais)</h5>
                    <div id="anexos-container">
                        <input type="file" name="anexos[]" class="form-control mb-2" multiple>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Registrar Chamado</button>
                        <a href="painel.php" class="btn btn-light">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="contato-template" style="display: none;">
        <div class="row g-3 mb-2 border p-2 rounded align-items-center">
            <div class="col-md-3">
                <input type="text" name="contato_nome[]" class="form-control" placeholder="Nome da Pessoa" required>
            </div>
            <div class="col-md-3">
                <input type="text" name="contato_telefone[]" class="form-control" placeholder="Telefone" required>
            </div>
            <div class="col-md-5">
                <input type="text" name="contato_obs[]" class="form-control" placeholder="Observação">
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger btn-sm remove-contato">X</button>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#descricao_problema').summernote({
                placeholder: 'Descreva o problema com o máximo de detalhes possível...',
                tabsize: 2,
                height: 200
            });

            $('#add-contato').click(function() {
                const contatoClone = $('#contato-template').children().clone(true, true);
                $('#contatos-container').append(contatoClone);
                $('input[name="contato_telefone[]"]').mask('(00) 00000-0000');
            });

            $(document).on('click', '.remove-contato', function() {
                $(this).closest('.row').remove();
            });

            $('#form-abrir-chamado').on('submit', function(e) {
                e.preventDefault();
                const form = this;
                const submitButton = $(this).find('button[type="submit"]');
                const formMessages = $('#form-messages');

                submitButton.prop('disabled', true).text('Enviando...');
                formMessages.html('').removeClass('alert alert-danger alert-success');

                if ($('#descricao_problema').summernote('isEmpty')) {
                    formMessages.html('A descrição do problema é obrigatória.').addClass('alert alert-danger');
                    submitButton.prop('disabled', false).text('Registrar Chamado');
                    return;
                }

                const formData = new FormData(form);
                const files = $('input[name="anexos[]"]')[0].files;
                const filePromises = [];

                const readFileAsBase64 = (file) => {
                    return new Promise((resolve, reject) => {
                        const reader = new FileReader();
                        reader.onload = () => resolve({
                            name: file.name,
                            data: reader.result
                        });
                        reader.onerror = (error) => reject(error);
                        reader.readAsDataURL(file);
                    });
                };

                for (let i = 0; i < files.length; i++) {
                    filePromises.push(readFileAsBase64(files[i]));
                }

                Promise.all(filePromises).then(base64files => {
                    formData.delete('anexos[]');
                    base64files.forEach(file => {
                        formData.append('anexos_base64[]', file.data);
                        formData.append('anexos_nomes[]', file.name);
                    });

                    formData.set('descricao_problema', $('#descricao_problema').summernote('code'));

                    $.ajax({
                        url: 'backend/chamados/processa_abertura.php',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                formMessages.html('Chamado aberto com sucesso! Redirecionando...').addClass('alert alert-success');
                                setTimeout(() => {
                                    window.location.href = 'painel.php';
                                }, 2000);
                            } else {
                                formMessages.html(response.message).addClass('alert alert-danger');
                                submitButton.prop('disabled', false).text('Registrar Chamado');
                            }
                        },
                        error: function() {
                            formMessages.html('Ocorreu um erro no servidor.').addClass('alert alert-danger');
                            submitButton.prop('disabled', false).text('Registrar Chamado');
                        }
                    });
                }).catch(error => {
                    formMessages.html('Erro ao processar anexos.').addClass('alert alert-danger');
                    submitButton.prop('disabled', false).text('Registrar Chamado');
                });
            });
        });
    </script>
</body>

</html>