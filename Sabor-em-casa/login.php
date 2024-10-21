<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="img/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/login.css">
    <title>Login</title>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <img src="img/logo.png" alt="Logo">
        </div>
        <h2>Login</h2>
        <form action="scripts/ValidaLogin.php" method="POST">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="seu@email.com" required>
            </div>
            <div class="form-group">
                <label for="senha">Senha:</label>
                <input type="password" class="form-control" id="senha" name="senha" placeholder="Sua Senha" required>
            </div>
            <?php if (isset($_GET['login']) && $_GET['login'] == 'erro1') { ?>
                <div class="error-message">
                    Usuário ou senha incorretos. Tente novamente.
                </div>
            <?php } ?>
            <button type="submit" class="btn btn-login">Entrar</button>
        </form>
        <p class="footer-text">Ainda não tem uma conta? <a href="registrar.php">Cadastre-se aqui</a></p>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>