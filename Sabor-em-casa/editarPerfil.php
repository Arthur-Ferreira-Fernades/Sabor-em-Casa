<?php
require('scripts/conectaBanco.php');
require('scripts/estaLogado.php');

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
$query = "SELECT UsuNome, UsuTelefone, UsuImagem FROM usuarios WHERE UsuId = :usuario_id";
$stmt = $conexao->prepare($query);
$stmt->bindParam(':usuario_id', $_SESSION['UsuarioId'], PDO::PARAM_INT);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

// Redireciona caso o usuário não seja encontrado
if (!$usuario) {
    header("Location: suaArea.php?mensagem=Usuário não encontrado.");
    exit();
}

// Captura a mensagem de sucesso ou erro, se existir
$mensagem = isset($_GET['mensagem']) ? htmlspecialchars($_GET['mensagem']) : '';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/editarPerfil.css">
    <link rel="stylesheet" href="styles/cabecalho.css">
    <title>Editar Perfil</title>
</head>
<body>
<header>
    <div class="cabecalho">
        <img src="img/logo.png" alt="Logo" width="80px">
        <a href="index.php" class="nav-link">Página Inicial</a>
        <a href="enviarReceita.php" class="nav-link">Enviar Receita</a>
        <div class="dropdown">
            <button class="btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="ClientesFoto/<?php echo $imagemPerfil; ?>" alt="" class="img-perfil" width="40px">
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
    <h2>Editar Perfil</h2>

    <!-- Mensagem de sucesso ou erro -->
    <?php if ($mensagem): ?>
        <div class="alert alert-<?php echo strpos($mensagem, 'Erro') === false ? 'success' : 'danger'; ?>" role="alert">
            <?php echo $mensagem; ?>
        </div>
    <?php endif; ?>

    <form action="scripts/validaEdicaoPerfil.php" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" class="form-control" id="nome" name="nome" value="<?php echo htmlspecialchars($usuario['UsuNome']); ?>" required>
        </div>
        
        <div class="mb-3">
            <label for="telefone" class="form-label">Telefone</label>
            <input type="text" class="form-control" id="telefone" name="telefone" value="<?php echo htmlspecialchars($usuario['UsuTelefone']); ?>" required>
        </div>
        
        <div class="mb-3">
            <label for="imagem" class="form-label">Imagem de Perfil</label>
            <input type="file" class="form-control" id="imagem" name="imagem">
            <img src="ClientesFoto/<?php echo htmlspecialchars($usuario['UsuImagem']); ?>" alt="Imagem de Perfil" class="img-thumbnail mt-2" width="200">
        </div>
        
        <button type="submit" class="btn btn-success">Salvar Alterações</button>
        <a href="suaArea.php" class="btn btn-secondary">Cancelar</a>
    </form>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
