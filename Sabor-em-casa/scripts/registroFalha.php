<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles/registroFalha.css">
    <title>Erro no Cadastro</title>

</head>

<body>
    <div class="error-container">
        <div class="logo">
            <img src="../img/logo.png" alt="Logo">
        </div>

        <div class="error-icon">
            &#10006;
        </div>

        <h2>Erro no Cadastro</h2>
        <p>Infelizmente, houve um erro ao processar seu cadastro. Por favor, tente novamente.</p>

        <a href="../registrar.php" class="btn btn-try-again">Tentar Novamente</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
