<?php
require('conectaBanco.php');
session_start();

if (!isset($_SESSION['usuario_validado']) || $_SESSION['usuario_validado'] != true) {
    echo json_encode(['removido' => false]);
    exit;
}

$usuario_id = $_SESSION['UsuarioId'];
$receita_id = $_GET['receitaId'];

// Remove a receita dos favoritos
$query = "DELETE FROM favoritos WHERE usuario_id = :usuario_id AND receita_id = :receita_id";
$stmt = $conexao->prepare($query);
$stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
$stmt->bindParam(':receita_id', $receita_id, PDO::PARAM_INT);

$removido = $stmt->execute();

echo json_encode(['removido' => $removido]);
?>
