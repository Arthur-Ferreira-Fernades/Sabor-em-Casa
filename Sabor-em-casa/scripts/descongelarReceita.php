<?php
require('conectaBanco.php'); // Certifique-se de que este arquivo conecta ao banco de dados
require('estaLogado.php'); // Verifique se o usuário está logado

// Verificar se o ID da receita foi passado na URL
if (isset($_GET['id'])) {
    $id_receita = $_GET['id'];

    // Preparar a consulta para descongelar a receita
    $query = "UPDATE receitas SET congelado = 0 WHERE Id_receita = :id_receita AND criador = :usuario_id";
    $stmt = $conexao->prepare($query);

    // Bind dos parâmetros
    $stmt->bindParam(':id_receita', $id_receita, PDO::PARAM_INT);
    $stmt->bindParam(':usuario_id', $_SESSION['UsuarioId'], PDO::PARAM_INT);

    // Executar a consulta
    if ($stmt->execute()) {
        // Redirecionar de volta para a página das receitas
        header("Location: ../suasReceitas.php?mensagem=Receita descongelada com sucesso!");
        exit();
    } else {
        // Caso haja um erro na execução
        header("Location: ../suasReceitas.php?mensagem=Erro ao descongelar a receita.");
        exit();
    }
} else {
    // Se não houver ID, redirecionar com mensagem de erro
    header("Location: suaArea.php?mensagem=ID da receita não especificado.");
    exit();
}
?>
