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
        $imagemPerfil = $usuario['UsuImagem'];
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="img/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/cabecalho.css">
    <link rel="stylesheet" href="styles/suaArea.css">
    <title>Sua Área</title>
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
                    <li><a class="dropdown-item" href="suaArea.php">Sua área</a></li>
                    <li><a class="dropdown-item" href="scripts/LogOff.php">Sair</a></li>
                </ul>
            </div>
        </div>
    </header>
    <main class="container mt-5">
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card custom-card text-center">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <h5 class="card-title">Perfil do Usuário</h5>
                        <p class="card-text">Edite suas informações pessoais e mantenha seu perfil atualizado.</p>
                        <a href="editarPerfil.php" class="btn custom-btn">Acessar Perfil</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card custom-card text-center">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <h5 class="card-title">Minhas Receitas</h5>
                        <p class="card-text">Visualize e gerencie todas as receitas que você enviou.</p>
                        <a href="suasReceitas.php" class="btn custom-btn">Ver Receitas</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card custom-card text-center">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <h5 class="card-title">Receitas Favoritas</h5>
                        <p class="card-text">Acesse rapidamente suas receitas favoritas salvas.</p>
                        <a href="receitasFavoritas.php" class="btn custom-btn">Ver Favoritas</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card custom-card text-center">
                    <div class="card-body d-flex flex-column justify-content-center">
                        <h5 class="card-title">Configurações de Conta</h5>
                        <p class="card-text">Ajuste suas credenciais como email e senha.</p>
                        <a href="editarCredenciais.php" class="btn custom-btn">Configurações</a>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>