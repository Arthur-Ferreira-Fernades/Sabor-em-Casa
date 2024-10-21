<?php
require('conectaBanco.php'); // Conexão com o banco de dados
require('estaLogado.php'); // Verifica se o usuário está logado

// Coleta os dados do formulário
$nome = $_POST['nome'];
$telefone = $_POST['telefone'];
$id_usuario = $_SESSION['UsuarioId'];

// Lidar com o upload da nova imagem, se houver
$imagem = $_FILES['imagem']['name'] ? basename($_FILES['imagem']['name']) : null;

if ($imagem) {
    move_uploaded_file($_FILES['imagem']['tmp_name'], $imagem);
}

// Atualiza os dados do usuário no banco de dados
$query = "
    UPDATE usuarios 
    SET UsuNome = :nome, UsuTelefone = :telefone" . 
    ($imagem ? ", UsuImagem = :imagem" : "") . 
    " WHERE UsuId = :usuario_id
";
$stmt = $conexao->prepare($query);
$stmt->bindParam(':nome', $nome);
$stmt->bindParam(':telefone', $telefone);
if ($imagem) {
    $stmt->bindParam(':imagem', $imagem);
}
$stmt->bindParam(':usuario_id', $id_usuario, PDO::PARAM_INT);

// Executa a atualização
if ($stmt->execute()) {
    header("Location: ../editarPerfil.php?mensagem=Dados atualizados com sucesso.");
} else {
    header("Location: ../editarPerfil.php?mensagem=Erro ao atualizar os dados.");
}
?>
