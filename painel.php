<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

require_once 'backend/db_connect.php';

$usuario_id = $_SESSION['usuario_id'];
$sql = "SELECT id, tipo_incidente, status, data_abertura FROM chamados WHERE usuario_id = ? ORDER BY data_abertura DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Chamados</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">Plataforma de Chamados</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <span class="navbar-text text-white me-3">
                            Olá, <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?>!
                        </span>
                    </li>
                    <li class="nav-item">
                        <a href="backend/auth/logout.php" class="btn btn-danger">Sair</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Meus Chamados</h2>
            <a href="abrir_chamado.php" class="btn btn-success">Abrir Novo Chamado</a>
        </div>

        <div class="card">
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#ID</th>
                            <th>Tipo de Incidente</th>
                            <th>Status</th>
                            <th>Data de Abertura</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($chamado = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $chamado['id']; ?></td>
                                    <td><?php echo htmlspecialchars($chamado['tipo_incidente']); ?></td>
                                    <td><span class="badge bg-info"><?php echo htmlspecialchars($chamado['status']); ?></span></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($chamado['data_abertura'])); ?></td>
                                    <td>
                                        <a href="ver_chamado.php?id=<?php echo $chamado['id']; ?>" class="btn btn-primary btn-sm">Visualizar</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">Nenhum chamado encontrado.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
<?php
$stmt->close();
$conn->close();
?>