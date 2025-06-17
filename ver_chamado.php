<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

require_once 'backend/db_connect.php';

$chamado_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($chamado_id <= 0) {
    die("ID de chamado inválido.");
}

$usuario_id = $_SESSION['usuario_id'];

$sql = "SELECT c.*, u.nome_completo FROM chamados c JOIN usuarios u ON c.usuario_id = u.id WHERE c.id = ? AND c.usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $chamado_id, $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("Acesso negado ou chamado não encontrado.");
}
$chamado = $result->fetch_assoc();
$stmt->close();

$contatos = $conn->query("SELECT * FROM chamados_contatos WHERE chamado_id = $chamado_id")->fetch_all(MYSQLI_ASSOC);
$anexos = $conn->query("SELECT id, nome_arquivo FROM chamados_anexos WHERE chamado_id = $chamado_id")->fetch_all(MYSQLI_ASSOC);
$historico = $conn->query("SELECT h.*, u.nome_completo FROM chamados_historico h JOIN usuarios u ON h.usuario_id = u.id WHERE h.chamado_id = $chamado_id ORDER BY h.data_ocorrencia ASC")->fetch_all(MYSQLI_ASSOC);

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Chamado #<?php echo $chamado_id; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5 mb-5">
        <a href="painel.php" class="btn btn-secondary mb-3">Voltar para o Painel</a>
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3>Chamado #<?php echo $chamado_id; ?> - <?php echo htmlspecialchars($chamado['tipo_incidente']); ?></h3>
            </div>
            <div class="card-body">
                <p><strong>Status:</strong> <span class="badge bg-info"><?php echo htmlspecialchars($chamado['status']); ?></span></p>
                <p><strong>Aberto por:</strong> <?php echo htmlspecialchars($chamado['nome_completo']); ?></p>
                <p><strong>Data de Abertura:</strong> <?php echo date('d/m/Y H:i', strtotime($chamado['data_abertura'])); ?></p>

                <hr>
                <h5>Descrição do Problema</h5>
                <div class="p-3 border rounded bg-light">
                    <?php echo $chamado['descricao_problema'];
                    ?>
                </div>

                <?php if (!empty($contatos)): ?>
                    <hr>
                    <h5>Contatos Associados</h5>
                    <ul class="list-group">
                        <?php foreach ($contatos as $contato): ?>
                            <li class="list-group-item">
                                <strong><?php echo htmlspecialchars($contato['nome_contato']); ?></strong> -
                                Tel: <?php echo htmlspecialchars($contato['telefone_contato']); ?>
                                <em>(<?php echo htmlspecialchars($contato['observacao']); ?>)</em>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

                <?php if (!empty($anexos)): ?>
                    <hr>
                    <h5>Anexos</h5>
                    <ul class="list-group">
                        <?php foreach ($anexos as $anexo): ?>
                            <li class="list-group-item">
                                <a href="backend/chamados/download_anexo.php?id=<?php echo $anexo['id']; ?>" target="_blank"><?php echo htmlspecialchars($anexo['nome_arquivo']); ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

                <hr>
                <h5>Histórico do Chamado (Timeline)</h5>
                <div class="list-group">
                    <?php foreach ($historico as $item): ?>
                        <div class="list-group-item">
                            <p class="mb-1"><?php echo htmlspecialchars($item['descricao']); ?></p>
                            <small class="text-muted"><?php echo htmlspecialchars($item['nome_completo']); ?> em <?php echo date('d/m/Y H:i', strtotime($item['data_ocorrencia'])); ?></small>
                        </div>
                    <?php endforeach; ?>
                </div>

                <hr class="mt-4">
                <h4>Adicionar Interação ou Anexo</h4>

                <div class="card mt-3">
                    <div class="card-body">
                        <form id="form-add-historico">
                            <input type="hidden" name="chamado_id" value="<?php echo $chamado_id; ?>">
                            <div class="mb-3">
                                <label for="nova_descricao" class="form-label"><strong>Nova Interação:</strong></label>
                                <textarea name="nova_descricao" id="nova_descricao" class="form-control" rows="3" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-info">Adicionar Comentário</button>
                        </form>
                        <div id="historico-messages" class="mt-2"></div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-body">
                        <form id="form-add-anexo">
                            <input type="hidden" name="chamado_id" value="<?php echo $chamado_id; ?>">
                            <div class="mb-3">
                                <label for="novos_anexos" class="form-label"><strong>Adicionar Novos Anexos:</strong></label>
                                <input type="file" name="anexos[]" id="novos_anexos" class="form-control" multiple required>
                            </div>
                            <button type="submit" class="btn btn-warning">Adicionar Anexos</button>
                        </form>
                        <div id="anexo-messages" class="mt-2"></div>
                    </div>
                </div>

                <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
                <script>
                    $(document).ready(function() {
                        $('#form-add-historico').on('submit', function(e) {
                            e.preventDefault();
                            const form = $(this);
                            $.ajax({
                                url: 'backend/chamados/adicionar_historico.php',
                                type: 'POST',
                                data: form.serialize(),
                                dataType: 'json',
                                success: function(response) {
                                    if (response.success) {
                                        alert(response.message);
                                        location.reload();
                                    } else {
                                        alert(response.message);
                                    }
                                }
                            });
                        });

                        $('#form-add-anexo').on('submit', function(e) {
                            e.preventDefault();
                            const form = this;
                            const formData = new FormData(form);
                            const files = $('#novos_anexos')[0].files;
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

                                $.ajax({
                                    url: 'backend/chamados/adicionar_anexo.php',
                                    type: 'POST',
                                    data: formData,
                                    processData: false,
                                    contentType: false,
                                    dataType: 'json',
                                    success: function(response) {
                                        if (response.success) {
                                            alert(response.message);
                                            location.reload();
                                        } else {
                                            alert(response.message);
                                        }
                                    }
                                });
                            });
                        });
                    });
                </script>

            </div>

        </div>
    </div>
</body>

</html>
<?php $conn->close(); ?>