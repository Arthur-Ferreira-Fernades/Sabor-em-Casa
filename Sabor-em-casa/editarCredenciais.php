<?php
require('scripts/conectaBanco.php');
require('scripts/estaLogado.php');

// Coleta o ID do usuário logado
$usuario_id = $_SESSION['UsuarioId'];

if (isset($_SESSION['usuario_validado']) && $_SESSION['usuario_validado'] == true) {
    $usuario_id = $_SESSION['UsuarioId'];
    $query = "SELECT UsuImagem FROM usuarios WHERE UsuId = :usuario_id";
    $stmt = $conexao->prepare($query);
    $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    $imagemPerfil = $usuario['UsuImagem']; // Obtém a imagem de perfil
}

// Busca os dados do usuário
$query = "SELECT UsuEmail, UsuSenha FROM usuarios WHERE UsuId = :usuario_id";
$stmt = $conexao->prepare($query);
$stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$usuario) {
    header("Location: login.php?mensagem=Usuário não encontrado.");
    exit();
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/cabecalho.css">
    <title>Atualizar Dados de Acesso</title>
</head>
<body>
<header>
        <div class="cabecalho">
            <img src="img/logo.png" alt="Logo" width="80px">
            <a href="index.php" class="nav-link">Página Inicial</a>
            <a href="enviarReceita.php" class="nav-link">Enviar Receita</a>
            <div class="dropdown">
                <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="ClientesFoto/<?php echo $imagemPerfil; ?>" alt="" class="img-perfil"  width ="40px" >
                </button>
                <ul class="dropdown-menu">
                    <?php if (!isset($_SESSION['usuario_validado']) || $_SESSION['usuario_validado'] == false) { ?>
                        <li><a class="dropdown-item" href="login.php">Fazer Login</a></li>
                    <?php } else { ?>
                        <li><a class="dropdown-item" href="suaArea.php">Sua área</a></li>
                        <li><a class="dropdown-item" href="scripts/LogOff.php">Sair</a></li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </header>

    <main class="container mt-5">
        <h2>Atualizar Dados de Acesso</h2>
        <form action="scripts/ValidaEdicaoCredenciais.php" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($usuario['UsuEmail']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="senha" class="form-label">Nova Senha</label>
                <input type="password" class="form-control" id="senha" name="senha" placeholder="Digite a nova senha">
            </div>
            <button type="submit" class="btn btn-success">Salvar Alterações</button>
            <a href="suaArea.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
