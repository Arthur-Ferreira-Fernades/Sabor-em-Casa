<?php
require('conectaBanco.php');
session_start();

if (!isset($_SESSION['usuario_validado']) || $_SESSION['usuario_validado'] != true) {
    echo json_encode(['favoritado' => false]);
    exit;
}

$usuarioId = $_SESSION['UsuarioId'];
$receitaId = $_GET['receitaId'];

// Verifica se a receita já está nos favoritos
$query = "SELECT * FROM favoritos WHERE usuario_id = :usuarioId AND receita_id = :receitaId";
$stmt = $conexao->prepare($query);
$stmt->bindParam(':usuarioId', $usuarioId, PDO::PARAM_INT);
$stmt->bindParam(':receitaId', $receitaId, PDO::PARAM_INT);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    // Remove do favoritos
    $query = "DELETE FROM favoritos WHERE usuario_id = :usuarioId AND receita_id = :receitaId";
    $stmt = $conexao->prepare($query);
    $stmt->execute([':usuarioId' => $usuarioId, ':receitaId' => $receitaId]);
    echo json_encode(['favoritado' => false]);
} else {
    // Adiciona aos favoritos
    $query = "INSERT INTO favoritos (usuario_id, receita_id) VALUES (:usuarioId, :receitaId)";
    $stmt = $conexao->prepare($query);
    $stmt->execute([':usuarioId' => $usuarioId, ':receitaId' => $receitaId]);
    echo json_encode(['favoritado' => true]);
}
?>
