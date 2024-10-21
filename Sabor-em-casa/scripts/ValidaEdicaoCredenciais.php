<?php
require('conectaBanco.php'); // Conexão com o banco de dados
require('estaLogado.php'); // Verifica se o usuário está logado

// Coleta o ID do usuário logado
$usuario_id = $_SESSION['UsuarioId'];

// Coleta os dados do formulário
$email = $_POST['email'];
$senha = $_POST['senha'];

// Atualiza os dados do usuário
$query = "UPDATE usuarios SET UsuEmail = :email" . 
         ($senha ? ", UsuSenha = :senha" : "") . 
         " WHERE UsuId = :usuario_id";
$stmt = $conexao->prepare($query);
$stmt->bindParam(':email', $email);
if ($senha) {
    $stmt->bindParam(':senha', $senha);
}
$stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);

// Executa a atualização
if ($stmt->execute()) {
    header("Location: ../editarCredenciais.php?mensagem=Dados atualizados com sucesso.");
} else {
    header("Location: ../editarCredenciais.php?mensagem=Erro ao atualizar os dados.");
}
?>
