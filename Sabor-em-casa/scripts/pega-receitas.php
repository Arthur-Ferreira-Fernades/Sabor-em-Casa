<?php
require('conectaBanco.php');
session_start();

// Pegando a categoria da requisição GET
$categoria = $_GET['categoria'];

// Inicializa a variável para armazenar o ID do usuário, se estiver logado
$usuarioId = isset($_SESSION['usuario_validado']) && $_SESSION['usuario_validado'] ? $_SESSION['UsuarioId'] : null;

// Consulta SQL para pegar as receitas que não estão congeladas (congelado = 0)
$query = "
    SELECT r.*, u.UsuNome AS criador, GROUP_CONCAT(i.ingrediente SEPARATOR ', ') AS ingredientes 
    FROM receitas r
    LEFT JOIN ingredientes i ON r.Id_receita = i.Id_receita
    LEFT JOIN usuarios u ON r.criador = u.UsuId 
    WHERE r.categoria = :categoria
    AND r.congelado = 0
    GROUP BY r.Id_receita
";

$stmt = $conexao->prepare($query);
$stmt->bindParam(':categoria', $categoria, PDO::PARAM_STR);
$stmt->execute();

// Pega todas as receitas não congeladas da categoria
$receitas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Verifica se o usuário está logado e busca os favoritos
if ($usuarioId) {
    $favoritosQuery = "
        SELECT receita_id FROM favoritos WHERE usuario_id = :usuarioId
    ";
    $favoritosStmt = $conexao->prepare($favoritosQuery);
    $favoritosStmt->bindParam(':usuarioId', $usuarioId, PDO::PARAM_INT);
    $favoritosStmt->execute();
    
    // Armazena os IDs das receitas favoritas em um array
    $favoritados = $favoritosStmt->fetchAll(PDO::FETCH_COLUMN, 0);
    $favoritados = array_flip($favoritados); // Para facilitar a verificação
}

// Adiciona a informação de 'favoritado' em cada receita
foreach ($receitas as &$receita) {
    $receita['favoritado'] = isset($favoritados[$receita['Id_receita']]);
}

// Retorna o resultado como JSON
echo json_encode($receitas);
?>
