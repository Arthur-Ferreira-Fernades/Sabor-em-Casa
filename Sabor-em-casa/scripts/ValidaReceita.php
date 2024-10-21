<?php
session_start();
require('conectaBanco.php'); // Conecta ao banco de dados

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_validado']) || $_SESSION['usuario_validado'] == false) {
    header('location: ../login.php'); // Redireciona para a página de login se não estiver logado
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtém os dados do formulário
    $nomeReceita = $_POST['nome'];
    $metodoPreparo = $_POST['metodo'];
    $ingredientes = $_POST['ingredientes']; // Array de ingredientes
    $categoria = $_POST['categoria']; // Obtém a categoria
    $usuarioId = $_SESSION['UsuarioId']; // Obtém o ID do usuário logado

    // Inicializa a variável de nome da imagem como null
    $fotoNome = null;

    // Verifica se uma foto foi enviada e se não houve erro
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $foto = $_FILES['foto'];
        $diretorioFotos = '../ReceitaImagens/';
        $diretorioFotoInsercao = 'ReceitaImagens/';

        // Cria um nome único para a imagem
        $fotoNome = uniqid() . '-' . basename($foto['name']);
        $caminhoCompleto = $diretorioFotos . $fotoNome;
        $caminhoCompletoInsercao = $diretorioFotoInsercao . $fotoNome;


        // Tenta mover o arquivo
        if (!move_uploaded_file($foto['tmp_name'], $caminhoCompleto)) {
            header('location: receitaFalha.php?Erro=upload');
            exit;
        }
    }

    try {
        // Insere a receita na tabela receitas
        $stmt = $conexao->prepare("INSERT INTO receitas (nome, imagem, Criador, categoria, preparo) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nomeReceita, $caminhoCompletoInsercao, $usuarioId, $categoria, $metodoPreparo]);

        // Obtém o ID da receita recém inserida
        $idReceita = $conexao->lastInsertId();

        // Insere os ingredientes na tabela ingredientes
        $stmt = $conexao->prepare("INSERT INTO ingredientes (id_receita, ingrediente) VALUES (?, ?)");
        foreach ($ingredientes as $ingrediente) {
            $stmt->execute([$idReceita, $ingrediente]);
        }

        // Redireciona para uma página de sucesso
        header('location: receitaSucesso.php');
    } catch (PDOException $erro) {
        // Se ocorrer um erro, redireciona para uma página de falha
        header('location: receitaFalha.php?Erro=erro');
    }
} else {
    header('location: receitaFalha.php?Erro=metodo');
}
?>
