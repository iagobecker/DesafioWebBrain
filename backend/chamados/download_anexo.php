<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    die('Acesso negado.');
}

require_once '../db_connect.php';

$anexo_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($anexo_id <= 0) {
    die("ID de anexo inválido.");
}

$usuario_id = $_SESSION['usuario_id'];

$sql = "SELECT a.nome_arquivo, a.arquivo 
        FROM chamados_anexos a
        JOIN chamados c ON a.chamado_id = c.id
        WHERE a.id = ? AND c.usuario_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $anexo_id, $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    die("Anexo não encontrado ou acesso negado.");
}

$anexo = $result->fetch_assoc();
$nome_arquivo = $anexo['nome_arquivo'];
$base64_data = $anexo['arquivo'];

$data_parts = explode(',', $base64_data);
$file_data = base64_decode(end($data_parts));

header('Content-Description: File Transfer');
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . basename($nome_arquivo) . '"');
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . strlen($file_data));
flush();
echo $file_data;

$stmt->close();
$conn->close();
exit();
