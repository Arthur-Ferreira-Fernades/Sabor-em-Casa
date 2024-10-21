<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles/SucFal.css"> <!-- CSS separado -->
    <title>Falha ao Enviar Receita</title>
</head>
<body>
    <header class="text-center py-4">
        <img src="../img/logo.png" alt="Logo" width="80px">
        <h1 class="mt-2">Ocorreu um Erro!</h1>
    </header>

    <div class="container mt-5 text-center">
        <div class="alert alert-danger" role="alert">
            Não foi possível enviar sua receita. 😞
        </div>
        <p>Por favor, verifique as informações e tente novamente.</p>
        
        <!-- Usando a classe custom-btn corretamente -->
        <a href="../index.php" class="custom-btn">Voltar para o Início</a>
        <a href="../enviarReceita.html" class="custom-btn">Tentar Novamente</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
