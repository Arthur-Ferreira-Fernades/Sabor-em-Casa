<?php
require('conectaBanco.php'); // Conexão com o banco de dados
require('estaLogado.php'); // Verifica se o usuário está logado

// Verifica se o ID da receita foi passado
if (!isset($_GET['id'])) {
    header("Location: suaArea.php?mensagem=ID da receita não especificado.");
    exit();
}

$id_receita = $_GET['id'];

// Coleta os dados do formulário
$nome = $_POST['nome'];
$preparo = $_POST['preparo'];
$categoria = $_POST['categoria']; // Nova linha para pegar a categoria
$ingredientes = $_POST['ingredientes'];

// Lidar com o upload da nova imagem, se houver
$imagem = null;
if (isset($_FILES['imagem']) && $_FILES['imagem']['name']) {
    $imagem = 'ReceitaImagens/' . basename($_FILES['imagem']['name']);
    
    // Mover a imagem para a pasta ReceitaImagens
    if (!move_uploaded_file($_FILES['imagem']['tmp_name'], '../' . $imagem)) {
        header("Location: ../suasReceitas.php?mensagem=Erro ao salvar a imagem.");
        exit();
    }
}

// Atualiza a receita no banco de dados
$query = "
    UPDATE receitas 
    SET nome = :nome, preparo = :preparo, categoria = :categoria" . 
    ($imagem ? ", imagem = :imagem" : "") . 
    " WHERE Id_receita = :id_receita AND criador = :usuario_id
";
$stmt = $conexao->prepare($query);

$stmt->bindParam(':nome', $nome);
$stmt->bindParam(':preparo', $preparo);
$stmt->bindParam(':categoria', $categoria); // Bind da nova categoria
if ($imagem) {
    $stmt->bindParam(':imagem', $imagem);
}
$stmt->bindParam(':id_receita', $id_receita, PDO::PARAM_INT);
$stmt->bindParam(':usuario_id', $_SESSION['UsuarioId'], PDO::PARAM_INT);

// Executa a atualização
if ($stmt->execute()) {
    // Atualiza os ingredientes, primeiro remove os existentes
    $deleteQuery = "DELETE FROM ingredientes WHERE Id_receita = :id_receita";
    $deleteStmt = $conexao->prepare($deleteQuery);
    $deleteStmt->bindParam(':id_receita', $id_receita, PDO::PARAM_INT);
    $deleteStmt->execute();

    // Insere os novos ingredientes
    $insertQuery = "INSERT INTO ingredientes (Id_receita, ingrediente) VALUES (:id_receita, :ingrediente)";
    $insertStmt = $conexao->prepare($insertQuery);

    foreach ($ingredientes as $ingrediente) {
        $insertStmt->bindParam(':id_receita', $id_receita, PDO::PARAM_INT);
        $insertStmt->bindParam(':ingrediente', $ingrediente);
        $insertStmt->execute();
    }

    header("Location: ../suasReceitas.php?mensagem=Receita atualizada com sucesso.");
} else {
    header("Location: ../suasReceitas.php?mensagem=Erro ao atualizar a receita.");
}
?>
