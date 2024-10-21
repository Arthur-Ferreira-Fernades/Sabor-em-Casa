<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles/registroSucesso.css">
    <title>Cadastro Bem Sucedido</title>

</head>

<body>
    <div class="confirmation-container">
        <div class="logo">
            <img src="../img/logo.png" alt="Logo">
        </div>

        <div class="confirmation-icon">
            &#10004; <!-- Check mark icon -->
        </div>

        <h2>Cadastro Bem Sucedido!</h2>
        <p>Seu cadastro foi realizado com sucesso. Agora você pode fazer login e aproveitar nossos serviços.</p>

        <a href="../login.php" class="btn btn-home">Ir para o Login</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
